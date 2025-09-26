<?php

namespace App\Filters\Website;

use App\Filters\QueryFilter;

class VaccineFilter extends QueryFilter
{
    public function name($name)
    {
        return $this->builder->filterByName($name); // scopeFilterByName في الموديل
    }

    public function age($age)
    {
        return $this->builder->filterByAge($age); // scopeFilterByAge في الموديل
    }

    // فلترة حسب الحالة النشطة
    public function is_active($value)
    {
        return $this->builder->filterByIsActive($value);
    }
}
