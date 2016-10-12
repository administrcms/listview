<?php

namespace Administr\ListView;

use Administr\Form\RenderAttributesTrait;
use Administr\ListView\Columns\Actions;
use Administr\ListView\Contracts\Column;
use Administr\ListView\Filters\ListViewFilters;
use ArrayAccess;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

/**
 * @method ListView boolean($name, $label, array $options = [])
 * @method ListView date($name, $label, array $options = [])
 * @method ListView datetime($name, $label, array $options = [])
 * @method ListView text($name, $label, array $options = [])
 * @method ListView time($name, $label, array $options = [])
 */
class ListView
{
    use RenderAttributesTrait;

    protected $dataSource = null;
    protected $columns = [];
    protected $actions;
    protected $options = [];

    protected $request;

    protected $filters;

    public function __construct($dataSource = null)
    {
        $this->setDataSource($dataSource);
        $this->columns();

        $this->filters = new ListViewFilters();
        call_user_func([$this, 'filters'], $this->filters);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function render()
    {
        $columns = $this->columns;
        $values = $this->getValues();
        $attrs = $this->renderAttributes($this->options);

        $globalActions = $this->getActions('global');

        $paginationLinks = null;

        if( $this->dataSource instanceof Paginator ) {
            $paginationLinks = $this->dataSource->render();
        }

        $filters = $this->filters;

        return view('administr/listview::list', compact('columns', 'values', 'attrs', 'paginationLinks', 'globalActions', 'filters'));
    }

    public function add(Column $column)
    {
        $column->setRequest($this->request);
        $this->columns[$column->getName()] = $column;
        return $this;
    }

    public function actions($label, \Closure $definition)
    {
        return $this->add(new Actions('actions', $label, $definition));
    }

    /**
     * @param $name
     * @return \Administr\ListView\Columns\Column
     * @throws \Exception
     */
    public function column($name)
    {
        if(array_key_exists($name, $this->columns))
        {
            return $this->columns[$name];
        }

        throw new \Exception("Column {$name} not defined.");
    }

    public function define(\Closure $definition)
    {
        $definition($this);
    }

    public function setRequest($request)
    {
        $this->request = $request;
        return $this;
    }

    public function getSort()
    {
        return $this->request->get('sort', []);
    }

    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * Setup columns
     */
    protected function columns()
    {
    }

    /**
     * Setup ListViewFilters
     *
     * @param ListViewFilters $filter
     */
    protected function filters(ListViewFilters $filter)
    {
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

        if(count($this->columns) === 0) {
            throw new \Exception('Columns not set.');
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

        if($this->dataSource instanceof Paginator) {
            $data = $this->dataSource->toArray();
            $data = $data['data'];
        }

        foreach ($data as $index => $item) {
            foreach ($this->columns as $column) {
                if(Arr::has($item, $column->getName()))
                {
                    $values[$index][$column->getName()] = Arr::get($item, $column->getName());
                    continue;
                }

                $values[$index][$column->getName()] = null;
            }

            $values[$index] = array_merge($values[$index], $item);
        }

        return $values;
    }

    public function getActions($type = 'context')
    {
        try {
            $actions = $this->column('actions')->getActions($type);
        } catch (\Exception $e) {
            $actions = [];
        }

        return $actions;
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
        $class = '\Administr\ListView\Columns\\' . studly_case($name);

        if(!class_exists($class)) {
            $class = '\Administr\ListView\Columns\Text';
        }

        if(count($args) === 2)
        {
            $args[] = [];
        }

        return $this->add(app($class, $args));
    }

    public function __toString()
    {
        return $this->render();
    }
}