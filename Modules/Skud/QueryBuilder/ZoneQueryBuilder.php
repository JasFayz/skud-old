<?php

namespace Modules\Skud\QueryBuilder;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class ZoneQueryBuilder extends Builder
{
    public function whereName(?string $name)
    {
        return $this->when($name, function ($query) use ($name) {
            $query->where('name', 'ilike', '%' . $name . '%');
        });
    }


    public function whereType(?string $type)
    {
        return $this->when($type, function ($query) use ($type) {
            $query->where('zone_type', $type);
        });
    }

}
