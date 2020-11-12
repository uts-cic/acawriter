<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TransformsRequest as Middleware;

class ConvertEmptyStringsToNull extends Middleware
{
    /**
     * The names of the attributes that should not be converted to null if empty.
     *
     * @var array
     */
    protected $except = [
        'text_input',
    ];

    protected function transform($key, $value)
    {
        if (in_array($key, $this->except, true)) {
            return $value;
        }
        return is_string($value) && $value === '' ? null : $value;
    }
}
