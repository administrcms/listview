<?php

namespace Administr\Listview\Columns;


class DateTime extends Column
{
    public function getValue($value)
    {
        return $this
            ->toCarbon($value)
            ->format(
                $this->getFormat(config('administr.listview.datetime_format'))
            );
    }
}