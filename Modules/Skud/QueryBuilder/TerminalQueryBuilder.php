<?php

namespace Modules\Skud\QueryBuilder;

use Illuminate\Database\Eloquent\Builder;
use Modules\Skud\Entities\Terminal;

class TerminalQueryBuilder extends Builder
{
    public function whereName(?string $name)
    {
        return $this->when($name, function ($query) use ($name) {
            return $query->where('name', 'ilike', '%' . $name . '%');
        });
    }

    public function whereMode(?bool $mode)
    {
        return $this->when($mode === true || $mode === false, function ($query) use ($mode) {
            return $query->where('mode', $mode);
        });
    }

    public function whereIp(?string $ip_address)
    {
        return $this->when($ip_address, function ($query) use ($ip_address) {
            return $query->where('ip', 'like', '%' . $ip_address . '%');
        });
    }

    public function whereSerialNumber(?string $serial_number)
    {
        return $this->when($serial_number, function ($query) use ($serial_number) {
            return $query->where('serial_number', 'ilike', '%' . $serial_number . '%');
        });
    }

    public function whereStatus(?int $status)
    {
        return $this->when($status, function ($query) use ($status) {
            return $query->where('status', $status);
        });
    }

    public function active()
    {
        return $this->where('status', '=', Terminal::STATUS_ACTIVE);
    }

    public function whereZoneType(?string $type)
    {
        return $this->when($type, function ($query) use ($type) {
            return $query->whereHas('zone', function ($q) use ($type) {
                return $q->where('zone_type', $type);
            });
        });
    }
}
