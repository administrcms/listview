<?php

namespace Administr\ListView\Columns;


class Action extends Column
{
    public function __construct($name, $label, array $options = [])
    {
        $options['isGlobal'] = false;
        $options['icon'] = 'fa fa-file-o';
        parent::__construct($name, $label, $options);
    }

    public function setGlobal()
    {
        $this->options['isGlobal'] = true;
        return $this;
    }

    public function isGlobal()
    {
        return (bool)$this->options['isGlobal'];
    }

    public function url($url)
    {
        $this->options['url'] = $url;
        return $this;
    }

    public function icon($icon)
    {
        $this->options['icon'] = $icon;
        return $this;
    }

    public function isSortable()
    {
        return (bool)array_get($this->options, 'sortable');
    }

    public function sortDirection()
    {
        $sort = $this->request->get('sort', []);

        return array_has($sort, $this->getName()) ?
            'sort-' . array_get($sort, $this->getName()) : 'sort';
    }

    public function sortReverse($sort)
    {
        return $sort == 'asc' ? 'desc' : ($sort == 'desc' ? null : 'asc');
    }

    public function sortLink()
    {
        $sort = $this->request->get('sort', []);

        if(array_has($sort, $this->getName()))
        {
            $sort[$this->getName()] = $this->sortReverse($sort[$this->getName()]);
        } else {
            $sort[$this->getName()] = 'asc';
        }

        $q = ['sort' => $sort];

        return $this->request->fullUrlWithQuery($q);
    }
}