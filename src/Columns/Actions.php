<?php

namespace Administr\ListView\Columns;

class Actions extends Column
{
    protected $actions = [];
    protected $view = 'administr/listview::_actions';
    
    public function view($view)
    {
        $this->view = $view;
        return $this;
    }

    public function action($name, $label, array $options = [])
    {
        $this->actions[$name] = new Action($name, $label, $options);
        return $this->actions[$name];
    }

    public function getValue()
    {
        $actions = $this->getActions('context');

        return view($this->view, [
            'actions' => $this,
            'contextActions' => $actions,
        ]);
    }

    public function setContext($row)
    {
        parent::setContext($row);

        foreach($this->getActions() as $action) {
            $action->setContext($row);
        }
    }

    public function getActions($type = 'context')
    {
        if($type === 'context') {
            return array_filter($this->actions, function(Action $action) {
                return !$action->isGlobal() && $action->visible();
            });
        }

        $this->runDefinition();

        return array_filter($this->actions, function(Action $action) {
            return $action->isGlobal() && $action->visible();
        });
    }
}