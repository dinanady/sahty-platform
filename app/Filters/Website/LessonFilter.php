<?php

namespace App\Filters\Website;

use App\Filters\QueryFilter;

class LessonFilter extends QueryFilter
{
    public function date($date = 'desc')
    {
        return $this->builder->filterByDate($date);
    }
    
    public function courser($course )
    {
        return $this->builder->filterByCourse($course);
    }
}
