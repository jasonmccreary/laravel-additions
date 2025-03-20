<?php

namespace App\Traits;

trait FindBy
{
    public static function __callStatic($method, $arguments)
    {
        if (! str_starts_with(strtolower($method), 'findby')) {
            return parent::__callStatic($method, $arguments);
        }

        $column = str($method)->substr(6)->snake()->value();

        $query = static::query();

        if (isset($arguments[1])) {
            $query = $query->select($arguments[1]);
        }

        if (is_array($arguments[0])) {
            $query = $query->whereIn($column, $arguments[0])->get();
        } else {
            $query = $query->where($column, $arguments[0])->first();
        }

        return $query;
    }
}
