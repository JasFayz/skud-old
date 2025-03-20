<?php

namespace Modules\Skud\ViewModels;

use Illuminate\Contracts\Auth\Authenticatable;
use Modules\Skud\Entities\Zone;
use Modules\Skud\FilterData\ZoneFilterData;

class ZoneViewModel
{
    public function __construct(private Authenticatable $user, private ZoneFilterData $filter)
    {
    }

    public function zones()
    {
        return Zone::query()
            ->withFloor()
            ->withTerminals()
            ->withCompany()
            ->whereName($this->filter->name)
            ->whereType($this->filter->zone_type);
    }


    public function getPaginate()
    {
        return $this->zones()->paginate();
    }

    public function getAll()
    {
        return $this->zones()
            ->get();
    }


}
