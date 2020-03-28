<?php

namespace Administr\ListView\Contracts;


interface Column
{
    public function setContext($row);
    public function getValue();
    public function getName();
    public function getLabel();
}