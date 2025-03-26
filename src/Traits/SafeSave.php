<?php

namespace JMac\Additions\Traits;

use Illuminate\Support\ValidatedInput;

trait SafeSave
{
    public static function create(array|ValidatedInput $attributes)
    {
        $instance = new static;

        if (is_array($attributes)) {
            return $instance->newQuery()->create($attributes);
        }

        $instance->forceFill($attributes->all())->save();

        return $instance;
    }

    public function update(array|ValidatedInput $attributes = [], array $options = [])
    {
        if ($attributes instanceof ValidatedInput) {
            $this->forceFill($attributes->all());
            $attributes = [];
        }

        return parent::update($attributes, $options);
    }
}
