<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasFilters
{
    public function scopeApplyFilters($query, array $filters = [])
    {
        foreach ($filters as $key => $value) {
            if (is_null($value) || $value === '') {
                continue;
            }

            $scope = Str::studly($key); // ex: search -> Search
            $method = 'scope' . $scope;

            if (method_exists($this, $method)) {
                $query->{$key}($value);
            }
        }

        return $query;
    }
}
