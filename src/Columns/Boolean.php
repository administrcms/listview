<?php

namespace Administr\Listview\Columns;

class Boolean extends Column
{
    public function getValue($value)
    {
        return (bool)$value;
    }
}