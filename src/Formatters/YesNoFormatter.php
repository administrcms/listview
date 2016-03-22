<?php

namespace Administr\ListView\Formatters;

use Administr\ListView\Contracts\Formatter;

class YesNoFormatter implements Formatter
{
    public function format($value)
    {
        return (bool)$value ? '<span class="label label-success">да</span>' : '<span class="label label-danger">не</span>';
    }
}