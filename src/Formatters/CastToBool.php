<?php

namespace Administr\ListView\Formatters;


use Administr\ListView\Contracts\Formatter;

class CastToBool implements Formatter
{
    public function format($value, $field = null)
    {
        if(is_array($value)) {
            return (bool)array_get($value, $field);
        }

        return (bool)$value;
    }
}