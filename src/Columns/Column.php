<?php

namespace Administr\ListView\Columns;

use Administr\ListView\Contracts\Column as ColumnContract;
use Carbon\Carbon;
use Closure;

abstract class Column implements ColumnContract
{
    protected $name;
    protected $label;
    protected $value;
    protected $definition = null;
    protected $options = [];

    public function __construct($name, $label, array $options = [])
    {
        $this->name = $name;
        $this->label = $label;
        $this->options = $options;
    }

    public function define(Closure $definition)
    {
        $this->definition = $definition;
        return $this;
    }

    public function format(Closure $formatter)
    {
        $formatter($this);
    }

    public function render($item = null)
    {
        if($this->definition instanceof Closure) {
            call_user_func_array($this->definition, [$this, $item]);
        }
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
        $this->render();
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

    public function __get($name)
    {
        if(array_key_exists($name, $this->options)) {
            return $this->options[$name];
        }

        return null;
    }

    public function __set($name, $value)
    {
        $this->options[$name] = $value;
    }
}