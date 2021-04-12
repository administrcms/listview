<?php

namespace Administr\ListView;

use Administr\Form\RenderAttributesTrait;
use Administr\ListView\Columns\Actions;
use Administr\ListView\Contracts\Column;
use Administr\Filters\Filters;
use ArrayAccess;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

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

        $this->filters = new Filters();
    }

    /**
     * @param string $view
     * @return mixed
     * @throws \Exception
     */
    public function render($view = 'administr/listview::list')
    {
        $columns = $this->getColumns();
        $values = $this->getValues();
        $attrs = $this->renderAttributes($this->options);

        $globalActions = $this->getActions('global');

        $paginationLinks = null;

        if( $this->dataSource instanceof Paginator ) {
            $paginationLinks = $this->dataSource;

            if(!is_null($this->request)) {
                $paginationLinks->appends($this->request->input());
            }

            $paginationLinks = $paginationLinks->render();
        }

        $filters = $this->getFilters();

        return view($view, compact('columns', 'values', 'attrs', 'paginationLinks', 'globalActions', 'filters'));
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
        if(array_key_exists($name, $this->getColumns()))
        {
            return $this->getColumns()[$name];
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
        call_user_func([$this, 'filters'], $this->filters);
        return $this->filters;
    }

    /**
     * Setup columns
     */
    protected function columns()
    {
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * Setup Filters
     *
     * @param Filters $filter
     */
    protected function filters(Filters $filter)
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

        if(count($this->getColumns()) === 0) {
            throw new \Exception('Columns not set.');
        }

        if (empty($this->getDataSource())) {
            return [];
        }

        return $this->getDataSource();
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
        $class = '\Administr\ListView\Columns\\' . Str::studly($name);

        if(!class_exists($class)) {
            $class = '\Administr\ListView\Columns\Text';
        }

        if(count($args) === 2)
        {
            $args[] = [];
        }

        return $this->add(app($class, [
            'name'      => $args[0],
            'label'     => $args[1],
            'options'   => $args[2],
        ]));
    }

    public function __toString()
    {
        return $this->render();
    }
}