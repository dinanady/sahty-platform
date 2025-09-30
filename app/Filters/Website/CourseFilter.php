<?php

namespace App\Filters\Admin;

use App\Filters\QueryFilter;

class CourseFilter extends QueryFilter
{
    public function date($date = 'desc')
    {
        return $this->builder->filterByDate($date);
    }

    public function name($name)
    {
        return $this->builder->filterByName($name);
    }
    
    public function free($free)
    {
        return $this->builder->filterByFree($free);
    }
}
