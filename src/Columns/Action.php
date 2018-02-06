<?php

namespace Administr\ListView\Columns;


class Action extends Column
{
    protected $view = 'administr/listview::_action';

    public function __construct($name, $label, array $options = [])
    {
        parent::__construct($name, $label, $options + [
            'isGlobal' => false,
            'icon' => 'fa fa-file-o',
        ]);
    }

    public function setGlobal()
    {
        return $this->set('isGlobal', true);
    }

    public function isGlobal()
    {
        return (bool)$this->get('isGlobal');
    }

    public function url($url)
    {
        return $this->set('url', $url);
    }
    
    public function route($name, array $parameters = [], $absolute = true)
    {
        return $this->url(route($name, $parameters, $absolute));
    }

    public function icon($icon)
    {
        return $this->set('icon', $icon);
    }

    public function view($view)
    {
        $this->view = $view;
        return $this;
    }

    public function getValue()
    {
        if($this->hidden()) {
            return;
        }

        return view($this->view, [
            'action' => $this,
        ]);
    }
}