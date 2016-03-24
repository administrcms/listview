<?php

namespace Administr\ListView\Contracts;


interface Column
{
    public function setContext(array $row);
    public function getValue();
    public function getName();
    public function getLabel();
}