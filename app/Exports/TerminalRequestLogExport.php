<?php

namespace App\Exports;

use Doctrine\DBAL\Types\DateImmutableType;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Modules\Skud\Entities\TerminalRequestLog;
use Modules\User\Entities\User;

class TerminalRequestLogExport implements FromQuery, WithMapping, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */

    use Exportable;

    public function __construct(private Carbon $date)
    {
    }

    public function query()
    {
        $terminalRequestLogs = TerminalRequestLog::query()
            ->select('id', 'date', 'terminal_id', 'identifier_number', 'terminal_mode')
            ->with(['identification', 'identification.identifiable' => function ($q) {
                return $q->select('id', 'name', 'email');
            }])
            ->whereHas('identification', function ($q) {
                return $q->where('model_type', '=', User::class);
            })
            ->filterByDate($this->formattedDate())
            ->latest();

        return $terminalRequestLogs;
    }

//    /**
//     * @var TerminalRequestLog $terminaLog
//     */

    public function map($terminaLog): array
    {
        return [
//            $terminaLog->id,
            $terminaLog->identifier_number,
            $terminaLog->identification->identifiable->name,
            $terminaLog->date,
            $terminaLog->terminal_id,
            $terminaLog->terminal_mode ? '1' : '0'
        ];
    }

    public function headings(): array
    {
        return ['UUID', 'FIO', 'Date', 'Terminal ID', 'Terminal Mode'];
    }

    public function formattedDate()
    {
        return $this->date->format('Y-m-d');
    }
}
