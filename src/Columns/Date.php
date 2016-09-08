<?php

namespace Administr\ListView\Columns;

class Date extends Column
{
    public function getValue()
    {
        return $this
            ->toCarbon(parent::getValue())
            ->format(
                $this->getFormat(config('administr.listview.date_format'))
            );
    }
}