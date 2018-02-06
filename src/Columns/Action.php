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
    
    public function route($name, array $parameters = [], $absolute = true)
    {
        return $this->url(route($name, $parameters, $absolute));
    }

    public function icon($icon)
    {
        $this->options['icon'] = $icon;
        return $this;
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