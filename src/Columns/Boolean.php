<?php

namespace Administr\ListView\Columns;

class Boolean extends Column
{
    public function getValue($value)
    {
        return (bool)$value;
    }
}