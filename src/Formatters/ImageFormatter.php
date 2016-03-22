<?php

namespace Administr\ListView\Formatters;


use Administr\ListView\Contracts\Formatter;

class ImageFormatter implements Formatter
{
    public function format($value)
    {
        return '<img src="'. $value .'" alt="" style="max-height: 50px" />';
    }
}