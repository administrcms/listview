<?php

namespace Administr\ListView\Columns;


class Time extends Column
{
    public function getValue()
    {
        return $this
            ->toCarbon(parent::getValue())
            ->format(
                $this->getFormat(config('administr.listview.time_format'))
            );
    }
}