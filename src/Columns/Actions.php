<?php

namespace Administr\ListView\Columns;

class Actions extends Column
{
    protected $actions = [];

    public function action($name, $label, array $options = [])
    {
        $this->actions[$name] = new Action($name, $label, $options);
        return $this->actions[$name];
    }

    public function getValue()
    {
        $actions = '';

        foreach($this->getActions('context') as $action) {
            $actions .= $action->getValue();
        }

        return $actions;
    }

    public function setContext(array $row)
    {
        parent::setContext($row);

        foreach($this->actions as $action) {
            $action->setContext($row);
        }
    }

    public function getActions($type = 'context')
    {
        $filter = function(Action $action) {
            return !$action->isGlobal() && $action->visible();
        };

        if($type === 'global') {
            $filter = function(Action $action) {
                return $action->isGlobal() && $action->visible();
            };
        }

        return array_filter($this->actions, $filter);
    }
}