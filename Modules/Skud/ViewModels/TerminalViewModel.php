<?php

namespace Modules\Skud\ViewModels;

use Modules\Skud\Entities\Terminal;
use Modules\Skud\FilterData\TerminalFilterData;

class TerminalViewModel
{
    public function __construct(private TerminalFilterData $filter)
    {
    }

    public function terminals()
    {
        return Terminal::query()
            ->with(['zone', 'company'])
            ->whereName($this->filter->name)
            ->whereIp($this->filter->ip)
            ->whereMode($this->filter->mode)
            ->whereSerialNumber($this->filter->serial_number)
            ->orderBy('name');
    }

    public function getPaginate()
    {
        return $this->terminals()->paginate();
    }

    public function getAll()
    {
        return $this->terminals()
            ->active()
            ->where('terminal_type', 'local')
//            ->doesntHave('zone')
            ->get();
    }
}
