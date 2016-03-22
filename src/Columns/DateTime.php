<?php

namespace Administr\ListView\Columns;


class DateTime extends Column
{
    public function getValue($value)
    {
        $value = parent::getValue($value);
        return $this
            ->toCarbon($value)
            ->format(
                $this->getFormat(config('administr.listview.datetime_format'))
            );
    }
}