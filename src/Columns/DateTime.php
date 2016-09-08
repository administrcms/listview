<?php

namespace Administr\ListView\Columns;


class DateTime extends Column
{
    public function getValue()
    {
        return $this
            ->toCarbon(parent::getValue())
            ->format(
                $this->getFormat(config('administr.listview.datetime_format'))
            );
    }
}