<?php

namespace Administr\ListView\Columns;

use Administr\ListView\Contracts\Column as ColumnContract;
use Administr\Form\RenderAttributesTrait;

use Carbon\Carbon;
use Closure;

abstract class Column implements ColumnContract
{
    use RenderAttributesTrait;

    protected $name;
    protected $label;
    protected $value;
    protected $definition = null;
    protected $formatters = [];
    protected $options = [];

    protected $currentRow = [];

    /**
     * @var \Illuminate\Http\Request
     */
    protected $request = null;

    /**
     * @var bool
     */
    protected $hideIf = false;

    public function __construct($name, $label, $options = null)
    {
        $this->name = $name;
        $this->label = $label;

        if($options instanceof Closure) {
            $this->define($options);
        }

        if(is_array($options) && count($options) > 0) {
            $this->options = $options;
        }
    }

    public function setRequest($request)
    {
        $this->request = $request;
        return $this;
    }

    /**
     * @return bool
     */
    public function sortable()
    {
        return (bool)array_get($this->options, 'sortable');
    }

    /**
     * @deprecated
     * @return bool
     */
    public function isSortable()
    {
        return $this->sortable();
    }

    /**
     * Get the current sort direction as string:
     * sort-asc, sort-desc or only sort when no
     * direction is set.
     *
     * @return string
     */
    public function sortDirection()
    {
        $sort = $this->request->get('sort', []);

        return array_has($sort, $this->getName()) ?
            'sort-' . array_get($sort, $this->getName()) : 'sort';
    }

    /**
     * Take a sort direction and reverse it.
     * null (no sort) => asc
     * asc => desc
     * desc => null (no sort)
     *
     * @param $sort
     * @return null|string
     */
    public function sortReverse($sort)
    {
        return $sort == 'asc' ? 'desc' : ($sort == 'desc' ? null : 'asc');
    }

    /**
     * Generate a sort link for this column.
     *
     * @return string
     */
    public function sortLink()
    {
        $sort = $this->request->get('sort', []);

        if(array_has($sort, $this->getName()))
        {
            $sort[$this->getName()] = $this->sortReverse($sort[$this->getName()]);
        } else {
            $sort[$this->getName()] = 'asc';
        }

        return $this->request->fullUrlWithQuery(['sort' => $sort]);
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

    public function runDefinition()
    {
        if(! $this->definition instanceof Closure) {
            return $this;
        }

        call_user_func_array($this->definition, [$this, $this->currentRow]);

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

        $this->formatters = $formatter;

        return $this;
    }

    /**
     * Sets the current row that is being rendered.
     *
     * If a definition is present, execute it with
     * the current row.
     *
     * This allows for data manipulation, like
     * changing the value of the column - make it
     * a link, display an image, etc.
     *
     * @param array $row
     */
    public function setContext(array $row)
    {
        $this->currentRow = $row;
        $this->runDefinition();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * The an associative array - key (column name) -> value.
     *
     * Return the value for a column or run value formatters,
     * if defined and return their result as value.
     *
     * @return mixed|null
     */
    public function getValue()
    {
        if(count($this->formatters) === 0) {
            return $this->currentRow[$this->getName()];
        }

        $value = $this->currentRow;

        foreach ($this->formatters as $formatter) {
            $value = $this->executeFormatter($formatter, $value);
        }

        return $value;
    }

    /**
     * Execute a value formatter.
     *
     * @param $formatter
     * @param $value
     * @return mixed
     */
    protected function executeFormatter($formatter, $value) {
        // Run the callback
        if($formatter instanceof Closure) {
            return call_user_func($formatter, $value);
        }

        // Passed a class, make instance and call the format method
        if(class_exists($formatter)) {
            return (new $formatter)->format($value);
        }

        // Passed a key that has to be matched to a class
        $formatters = config('administr.listview.formatters');

        if(!str_contains($formatter, ':')) {
            $formatter .= ':';
        }

        list($formatter, $args) = explode(':', $formatter);
        $args = array_merge([$value], explode(',', $args));
        
        if(array_key_exists($formatter, $formatters)) {
            $formatter = new $formatters[$formatter];
            return call_user_func_array([$formatter, 'format'], $args);
        }

        // No formatter, return value
        return $value;
    }

    /**
     * Show a column if a condition is met.
     *
     * @param $condition
     * @return Column
     */
    public function showIf($condition)
    {
        return $this->hideIf(!$condition);
    }

    /**
     * Hide a column if a condition is met.
     *
     * @param $condition
     * @return $this
     */
    public function hideIf($condition)
    {
        $this->hideIf = $condition;
        return $this;
    }

    /**
     * @return bool
     */
    public function visible()
    {
        return (bool)$this->hideIf === false;
    }

    /**
     * @return bool
     */
    public function hidden()
    {
        return !$this->visible();
    }

    /**
     * Parse value to Carbon instance.
     *
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
     * @param $name
     * @param $value
     * @return $this
     */
    public function set($name, $value)
    {
        $this->options[$name] = $value;
        return $this;
    }

    /**
     * @param $name
     * @return mixed|null
     */
    public function get($name)
    {
        if(array_key_exists($name, $this->options)) {
            return $this->options[$name];
        }

        return null;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        if(!is_array($this->options)) {
            return [];
        }

        return $this->options;
    }

    public function attributes()
    {
        return $this->renderAttributes($this->getOptions());
    }

    /**
     * Get a value format - for example,
     * you can set a format of date / time value.
     *
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

    public function render()
    {
        return $this->getValue();
    }

    public function __get($name)
    {
        return $this->get($name);
    }

    public function __set($name, $value)
    {
        return $this->set($name, $value);
    }
}