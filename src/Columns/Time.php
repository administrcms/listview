<?php

namespace Administr\ListView\Columns;


class Time extends Column
{
    public function getValue($value)
    {
        return $this
            ->toCarbon($value)
            ->format(
                $this->getFormat(config('administr.listview.time_format'))
            );
    }
}