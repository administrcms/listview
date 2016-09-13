<?php

namespace Administr\ListView\Traits;


trait Sortable
{
    public function scopeSorted(Builder $query)
    {
        foreach (request()->get('sort', []) as $column => $sort)
        {
            $query->orderBy($column, $sort);
        }

        return $query;
    }
}