<?php

namespace App\Filters\Website;

use App\Filters\QueryFilter;

class VaccineFilter extends QueryFilter
{
    public function name($name)
    {
        return $this->builder->name($name); 
    }

    public function age($age)
    {
        return $this->builder->age($age);
    }

    // فلترة حسب الحالة النشطة
    public function is_active($value)
    {
        return $this->builder->availability($value);
    }
}
