<?php

namespace Administr\ListView\Contracts;


interface Column
{
    public function getValue(array $row);
    public function getName();
}