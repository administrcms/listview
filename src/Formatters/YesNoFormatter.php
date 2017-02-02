<?php

namespace Administr\ListView\Formatters;

use Administr\ListView\Contracts\Formatter;

class YesNoFormatter implements Formatter
{
    public function format($value)
    {
        return (bool)$value ? '<span class="label label-success">' . trans('formatters.yesno.yes') . '</span>' : '<span class="label label-danger">' . trans('formatters.yesno.no') . '</span>';
    }
}
