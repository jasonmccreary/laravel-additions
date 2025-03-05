<?php

if (! function_exists('status')) {
    /**
     * Create a response with the given code.
     *
     * @param  int|null  $code
     * @return ($code is null ? \JMac\Additions\Support\HttpStatus : \Illuminate\Http\Response)
     */
    function status($code = null)
    {
        if (! is_null($code)) {
            // TODO: restrict 300 level status codes...

            return response()->noContent($code);
        }

        static $class;

        $class ??= new \JMac\Additions\Support\HttpStatus;

        return $class;
    }
}
