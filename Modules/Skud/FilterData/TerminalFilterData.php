<?php

namespace Modules\Skud\FilterData;

use Illuminate\Contracts\Support\Arrayable;

class TerminalFilterData implements Arrayable
{

    public function __construct(
        public ?string $name = null,
        public ?bool   $mode = null,
        public ?string $ip = null,
        public ?string $serial_number = null,
        public ?int    $status = null
    )
    {
    }

    public function toArray()
    {
        return [
            'name' => $this->name,
            'mode' => $this->mode,
            'ip' => $this->ip,
            'serial_number' => $this->serial_number,
            'status' => $this->status
        ];
    }

}
