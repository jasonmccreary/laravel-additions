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
        if (Str::endsWith($name, '_table')) {
            $name = Str::substr($name, 0, -6);
        }

        // normalize dashes... unnormalize...

        if (preg_match('/^(?:drop|remove)_(\w+)_from_(\w+)$/', $name, $matches)) {
            return [$matches[2], 'remove-column', ['column' => $matches[1]]];
        } elseif (preg_match('/^rename_(\w+)_to_(\w+)_in_(\w+)$/', $name, $matches)) {
            return [$matches[3], 'rename-column', ['column' => $matches[1], 'to' => $matches[2]]];
        } elseif (preg_match('/^add_(\w+)_to_(\w+)$/', $name, $matches)) {
            return [$matches[2], 'add-column', ['column' => $matches[1]]];
        } elseif (preg_match('/^(?:alter|change)_(\w+)_in_(\w+)$/', $name, $matches)) {
            return [$matches[2], 'change-column', ['column' => $matches[1]]];
        } elseif (preg_match('/^(?:drop|remove)_(\w+)$/', $name, $matches)) {
            return [$matches[1], 'drop-table', []];
        } elseif (preg_match('/^rename_(\w+)_to_(\w+)$/', $name, $matches)) {
            return [$matches[1], 'rename-table', ['to' => $matches[2]]];
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
