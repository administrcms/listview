<?php

namespace Administr\Listview\Columns;

use Administr\Listview\Contracts\Column as ColumnContract;
use Carbon\Carbon;

abstract class Column implements ColumnContract
{
    protected $name;
    protected $label;
    protected $value;
    protected $options = [];

    public function __construct($name, $label, array $options = [])
    {
        $this->name = $name;
        $this->label = $label;
        $this->options = $options;
    }

    public function format(\Closure $formatter)
    {
        $formatter($this);
    }

    public function getName()
    {
        return $this->name;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function getValue($value)
    {
        return $value;
    }

    /**
     * @param $value
     * @return Carbon
     */
    public function toCarbon($value)
    {
        if($value instanceof Carbon) {
            return $value;
        }

        return Carbon::parse($value);
    }

    /**
     * @param null $default
     * @return string
     */
    protected function getFormat($default = null)
    {
        if (array_key_exists('format', $this->options)) {
            return $this->options['format'];
        }

        return $default;
    }
}