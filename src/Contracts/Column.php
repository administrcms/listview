<?php

namespace Administr\Listview\Contracts;


interface Column
{
    public function getValue($value);
    public function getName();
}