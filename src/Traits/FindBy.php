<?php

namespace JMac\Additions\Traits;

trait FindBy
{
    // While the `findBy` methods are intended to be called
    // statically, this magic method is required to handle
    // `findBy` calls from within instance methods.
    //
    // https://x.com/gonedark/status/1963386192759783593
    public function __call($method, $arguments)
    {
        if (! str_starts_with(strtolower($method), 'findby')) {
            return parent::__call($method, $arguments);
        }

        return self::performFindByQuery($method, $arguments);
    }

    public static function __callStatic($method, $arguments)
    {
        if (! str_starts_with(strtolower($method), 'findby')) {
            return parent::__callStatic($method, $arguments);
        }

        return self::performFindByQuery($method, $arguments);
    }

    private static function performFindByQuery($method, $arguments)
    {
        $column = str($method)->substr(6)->snake()->value();

        $query = static::query();

        if (isset($arguments[1])) {
            $query = $query->select($arguments[1]);
        }

        if (is_array($arguments[0])) {
            return $query->whereIn($column, $arguments[0])->get();
        }

        return $query->where($column, $arguments[0])->first();
    }
}
