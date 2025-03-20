<?php

namespace Modules\Skud\FilterData;

use Illuminate\Contracts\Support\Arrayable;

class ZoneFilterData implements Arrayable
{
    public function __construct(

        public ?string $name = null,
        public ?bool   $is_attend = null,
        public ?string $zone_type = null,
        public ?string $floor_id = null,
        public ?string $company_id = null
    )
    {
    }

    public function toArray()
    {
        return [
            'name' => $this->name,
            'is_attend' => $this->is_attend,
            'zone_type' => $this->zone_type,
            'floor_id' => $this->floor_id,
            'company_id' => $this->company_id
        ];
    }

}
