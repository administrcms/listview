<?php

namespace Administr\ListView\Columns;

class Date extends Column
{
    public function getValue($value)
    {
        return $this
            ->toCarbon($value)
            ->format(
                $this->getFormat(config('administr.listview.date_format'))
            );
    }
}