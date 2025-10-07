<?php

namespace JMac\Additions\Support;

use Illuminate\Support\Str;

class MigrationCreator extends \Illuminate\Database\Migrations\MigrationCreator
{
    public function create($name, $path, $table = null, $create = false)
    {
        if (isset($table) && $create) {
            return parent::create($name, $path, $table, $create);
        }

        [$table, $type, $data] = $this->guessAction($name, $table);
        if (! $type) {
            return parent::create($name, $path, $table, $create);
        }

        $stub = $this->getStubForType($type);
        $path = $this->getPath($name, $path);
        $this->files->ensureDirectoryExists(dirname($path));
        $this->files->put($path, $this->populateStubForType($stub, $table, $type, $data));
        $this->firePostCreateHooks($table, $path);

        return $path;
    }

    private function extractValue(array $match, string $source): string
    {
        return Str::substr($source, $match[1], strlen($match[0]));
    }

    private function getStubForType(mixed $type): string
    {
        $stub = match ($type) {
            'add-column', 'change-column', 'rename-column' => 'migration.inner.stub',
            default => 'migration.top.stub',
        };

        return $this->files->get(__DIR__.'/../../stubs/'.$stub);
    }

    private function guessAction(string $name, ?string $table): array
    {
        // Before guessing, we'll ensure the name is delimited by
        // underscores and remove any "_table" suffix for easier
        // matching of the various supported migration types.
        $normalized = str_replace('-', '_', $name);
        if (Str::endsWith($normalized, '_table')) {
            $normalized = Str::substr($normalized, 0, -6);
        }

        if (preg_match('/^(?:drop|remove)_(\w+)_from_(\w+)$/', $normalized, $matches, PREG_OFFSET_CAPTURE)) {
            return [$this->extractValue($matches[2], $name), 'remove-column', ['column' => $this->extractValue($matches[1], $name)]];
        } elseif (preg_match('/^rename_(\w+)_to_(\w+)_in_(\w+)$/', $normalized, $matches, PREG_OFFSET_CAPTURE)) {
            return [$this->extractValue($matches[3], $name), 'rename-column', ['column' => $this->extractValue($matches[1], $name), 'to' => $this->extractValue($matches[2], $name)]];
        } elseif (preg_match('/^add_(\w+)_to_(\w+)$/', $normalized, $matches, PREG_OFFSET_CAPTURE)) {
            return [$this->extractValue($matches[2], $name), 'add-column', ['column' => $this->extractValue($matches[1], $name)]];
        } elseif (preg_match('/^(?:alter|change)_(\w+)_in_(\w+)$/', $normalized, $matches, PREG_OFFSET_CAPTURE)) {
            return [$this->extractValue($matches[2], $name), 'change-column', ['column' => $this->extractValue($matches[1], $name)]];
        } elseif (preg_match('/^(?:drop|remove)_(\w+)$/', $normalized, $matches, PREG_OFFSET_CAPTURE)) {
            return [$this->extractValue($matches[1], $name), 'drop-table', []];
        } elseif (preg_match('/^rename_(\w+)_to_(\w+)$/', $normalized, $matches, PREG_OFFSET_CAPTURE)) {
            return [$this->extractValue($matches[1], $name), 'rename-table', ['to' => $this->extractValue($matches[2], $name)]];
        }

        return [$table, null, []];
    }

    private function populateStubForType(string $stub, mixed $table, mixed $type, mixed $data): string
    {
        $action = match ($type) {
            'add-column' => '$table->string(\'{{column}}\');',
            'change-column' => '$table->string(\'{{column}}\')->change();',
            'rename-column' => '$table->renameColumn(\'{{column}}\', \'{{to}}\');',
            'remove-column' => 'Schema::dropColumns(\'{{table}}\', [\'{{column}}\']);',
            'drop-table' => 'Schema::dropIfExists(\'{{table}}\');',
            'rename-table' => 'Schema::rename(\'{{table}}\', \'{{to}}\');',
            default => '',
        };

        $stub = str_replace('//', $action, $stub);
        $stub = str_replace('{{column}}', $data['column'] ?? '', $stub);
        $stub = str_replace('{{to}}', $data['to'] ?? '', $stub);

        return parent::populateStub($stub, $table);
    }
}
