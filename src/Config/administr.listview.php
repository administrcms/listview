<?php

return [
    'date_format'       => 'd/m/Y',
    'time_format'       => 'H:i:s',
    'datetime_format'   => 'd/m/Y H:i:s',

    'formatters'        => [
        'yesno' => \Administr\ListView\Formatters\YesNoFormatter::class,
    ]
];