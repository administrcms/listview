<?php

namespace Administr\ListView;


use Administr\ListView\Contracts\Column;
use ArrayAccess;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class ListView
{
    protected $dataSource = null;
    protected $columns = [];
    protected $options = [];

    public function __construct($dataSource = null)
    {
        $this->setDataSource($dataSource);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function render()
    {
        $columns = $this->columns;
        $values = $this->getValues();

        return view('administr.listview::list', compact('columns', 'values'));
    }

    public function add(Column $column)
    {
        $this->columns[$column->getName()] = $column;
        return $this;
    }

    public function define(\Closure $definition)
    {
        $definition($this);
    }

    /**
     * @return null
     */
    public function getDataSource()
    {
        return $this->dataSource;
    }

    /**
     * @param null $dataSource
     * @return $this
     */
    public function setDataSource($dataSource)
    {
        $this->dataSource = $dataSource;
        return $this;
    }

    public function getValues()
    {
        if(is_null($this->dataSource)) {
            throw new \Exception('No data provided.');
        }

        $values = [];
        $data = [];

        if(is_array($this->dataSource) || $this->dataSource instanceof ArrayAccess) {
            $data = $this->dataSource;
        }

        if($this->dataSource instanceof Collection)
        {
            $data = $this->dataSource
                ->map(function(Model $item){
                    return $item->toArray();
                })
                ->toArray();
        }

        foreach ($data as $index => $item) {
            foreach ($this->columns as $column) {
                $values[$index][$column->getName()] = array_key_exists($column->getName(), $item) ? $item[$column->getName()] : null;
            }
        }

        return $values;
    }

    public function __get($name)
    {
        if(array_key_exists($name, $this->options))
        {
            return $this->options[$name];
        }

        return null;
    }

    public function __set($name, $value)
    {
        $this->options[$name] = $value;
        return $this;
    }

    public function __call($name, $args = [])
    {
        $class = '\Administr\Listview\Columns\\' . studly_case($name);

        if(!class_exists($class)) {
            $class = '\Administr\Listview\Columns\Text';
        }

        if(count($args) === 2)
        {
            $args[] = [];
        }

        if(count($args) === 3)
        {
            list($field, $label, $options) = $args;

            $args = [
                $field, $label, $options,
            ];
        }

        return $this->add(app($class, $args));
    }

    public function __toString()
    {
        return $this->render();
    }
}