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
            if ($code >= 300 && $code < 400) {
                throw new \InvalidArgumentException('You can not use the status helper for redirection');
            }

            return response()->noContent($code);
        }

        static $class;

        $class ??= new \JMac\Additions\Support\HttpStatus;

        return $class;
    }
}
