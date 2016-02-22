<?php

namespace Administr\ListView\Contracts;


interface Column
{
    public function getValue($value);
    public function getName();
}