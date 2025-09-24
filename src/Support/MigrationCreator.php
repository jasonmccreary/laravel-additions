<?php

namespace JMac\Additions\Support;

use Illuminate\Support\Str;

class MigrationCreator extends \Illuminate\Database\Migrations\MigrationCreator
{
    public function create($name, $path, $table = null, $create = false)
    {
        dump(func_get_args());

        if (isset($table) && $create) {
            return parent::create($name, $path, $table, $create);
        }

        [$table, $type, $data] = $this->guessAction($name, $table);
        if (! $type) {
            return parent::create($name, $path, $table, $create);
        }

        // TODO: it's guessable, populate the stub accordingly and write file
        dd($table, $type, $data);
    }

    private function guessAction(string $name, ?string $table): array
    {
        if (Str::endsWith($name, '_table')) {
            $name = Str::substr($name, 0, -6);
        }

        // normalize dashes... unnormalize...

        if (preg_match('/^(?:drop|remove)_(\w+)_(?:from|in)_(\w+)$/', $name, $matches)) {
            return [$matches[2], 'remove', [$matches[1]]];
        } elseif (preg_match('/^rename_(\w+)_to_(\w+)_in_(\w+)$/', $name, $matches)) {
            return [$matches[1], 'rename', [$matches[2], $matches[3]]];
        } elseif (preg_match('/^add_(\w+)_to_(\w+)$/', $name, $matches)) {
            return [$matches[1], 'add', [$matches[2]]];
        } elseif (preg_match('/^(?:drop|remove)_(\w+)$/', $name, $matches)) {
            return [$matches[1], 'drop', []];
        } elseif (preg_match('/^rename_(\w+)_to_(\w+)$/', $name, $matches)) {
            return [$matches[1], 'rename', [$matches[2]]];
        }

        return [$table, null, []];
    }
}
