<?php

namespace Modules\Skud\DTOs;

use Modules\Skud\Http\Requests\ZoneRequest;
use Spatie\LaravelData\Data;

class ZoneDTO extends Data
{
    public function __construct(
        public string $name,
        public bool   $is_calc_attend,
        public ?int   $floor_id,
        public string $zone_type,
        public array  $terminals,
        public ?int   $company_id,
    )
    {
    }

    public static function fromRequest(ZoneRequest $request)
    {
        return new self(
            name: trim($request->get('name')),
            is_calc_attend: $request->get('is_calc_attend'),
            floor_id: $request->get('floor_id'),
            zone_type: $request->get('zone_type'),
            terminals: $request->get('terminals'),
            company_id: $request->get('company_id')
        );
    }


}
