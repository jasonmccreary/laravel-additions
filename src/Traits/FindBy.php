<?php

namespace JMac\Additions\Traits;

trait FindBy
{
    public function __call(string $name, array $arguments)
    {
        if (! str_starts_with(strtolower($name), 'findby')) {
            return $this->__call($name, $arguments);
        }

        return self::findBy($name, $arguments);
    }

    public static function __callStatic($method, $arguments)
    {
        if (! str_starts_with(strtolower($method), 'findby')) {
            return parent::__callStatic($method, $arguments);
        }

        return self::findBy($method, $arguments);
    }

    private static function findBy($method, $arguments)
    {
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
