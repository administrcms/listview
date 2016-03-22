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
    protected $formatters = [];
    protected $options = [];

    public function __construct($name, $label, array $options = [])
    {
        $this->name = $name;
        $this->label = $label;
        $this->options = $options;
    }

    /**
     * Additional definition of the column.
     * It accepts a closure with two parameters - the column and the row data as array.
     *
     * It is a good place to define context actions, since you have access to the data.
     *
     * @param Closure $definition
     * @return $this
     */
    public function define(Closure $definition)
    {
        $this->definition = $definition;
        return $this;
    }

    /**
     * Format the output of the column.
     *
     * @param string|array|Closure $formatter
     * @return $this
     */
    public function format($formatter)
    {
        if(is_string($formatter) || $formatter instanceof Closure)
        {
            $formatter = func_get_args();
        }

        $this->formatters = array_merge($this->formatters, $formatter);

        return $this;
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
        if(count($this->formatters) === 0) {
            return $value[$this->getName()];
        }

        foreach ($this->formatters as $formatter) {
            $value = $this->resolveFormatter($formatter, $value);
        }

        return $value;
    }

    protected function resolveFormatter($formatter, $value) {

        // Run the callback
        if($formatter instanceof Closure) {
            return call_user_func_array($formatter, [$this, $value]);
        }

        // Passed a class, make instance and call the format method
        if(class_exists($formatter)) {
            return (new $formatter)->format($value);
        }

        // Passed a key that has to be matched to a class
        $formatters = config('administr.listview.formatters');
        if(array_key_exists($formatter, $formatters)) {
            return (new $formatters[$formatter])->format($value);
        }

        // No formatter, return value
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