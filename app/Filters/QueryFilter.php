<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;


abstract class QueryFilter
{


    protected $builder;

    public function __construct(protected Request $request) {}


    public function apply(Builder $builder)
    {

        $this->builder = $builder;
        foreach ($this->filters() as $name => $value) {
            if (! method_exists($this, $name)) {
                continue;
            }

            if (strLen($value)) {
                $this->$name($value);
            } else {
                $this->$name;
            }
        }

        return $this->builder;
    }

    public function filters()
    {
        return $this->request->all();
    }
}
