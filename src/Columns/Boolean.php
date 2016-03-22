<?php

namespace Administr\ListView\Columns;

class Boolean extends Column
{
    public function getValue($value)
    {
        $value = (bool)$value;
        return parent::getValue($value);
    }
}