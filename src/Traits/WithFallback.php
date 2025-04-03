<?php

namespace JMac\Additions\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait WithFallback
{
    public function __call($name, $arguments)
    {
        if (! method_exists($this, 'fallback')) {
            throw new \RuntimeException('You must define a fallback method when using the WithFallback trait');
        }

        $name = Str::snake($name, '-');
        $user = array_shift($arguments);
        $model = null;

        if (isset($arguments[0])
            && $arguments[0] instanceof Model
            && str_starts_with(class_basename(self::class), class_basename($arguments[0]::class))) {
            $model = array_shift($arguments);
        }

        return $this->fallback($name, $user, $model, $arguments);
    }
}
