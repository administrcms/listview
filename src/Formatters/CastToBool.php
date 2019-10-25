<?php

namespace Administr\ListView\Formatters;


use Administr\ListView\Contracts\Formatter;
use Illuminate\Support\Arr;

class CastToBool implements Formatter
{
    public function format($value, $field = null)
    {
        if(is_array($value)) {
            return (bool)Arr::get($value, $field);
        }

        return (bool)$value;
    }
}