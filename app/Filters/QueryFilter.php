<?php

namespace App\Filters;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class QueryFilter
{
    protected $request;
    protected $builder;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $builder)
    {
        $this->builder = $builder;
        foreach ($this->filters() as $name => $value) {
            if (method_exists($this, $name)) {
                $value = is_array($value) ? array_filter($value) : [$value];
                call_user_func_array([$this, $name], $value);
            }
        }
    }

    public function filters()
    {
        return $this->request->all();
    }
}
