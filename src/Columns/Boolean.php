<?php

namespace Administr\ListView\Columns;

class Boolean extends Column
{
    public function getValue()
    {
        return (bool)parent::getValue();
    }
}