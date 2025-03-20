<?php

namespace Modules\Skud\Services;

use Illuminate\Support\Facades\Log;
use Modules\Skud\Entities\IdentifierType;
use Modules\Skud\Entities\Terminal;
use Modules\DoorManage\Entities\DoorKeyLog;
use Modules\Skud\Entities\TerminalRequestLog;

class DoorKeyManageService
{

    public function __construct(private TerminalRequestLog $log)
    {
    }

    public function handle(TerminalRequestLog $log)
    {
        $this->log = $log;

        if ($this->isDoorKey($this->log->identification->model_type)) {
            Log::build([
                'driver' => 'single',
                'path' => storage_path('logs/debuging.log'),
            ])->debug(!$this->checkDoorKeyInLog() && $this->log->terminal->mode);

            if (!$this->checkDoorKeyInLog() && $this->log->terminal->mode) {
                $this->createDooKeyLog();
            } else if ($this->checkDoorKeyInLog() && !$this->log->terminal->mode) {
                $doorKeyLog = $this->getDoorKeyFromLog($this->log->identification->identifier_number);

                $doorKeyLog->update([
                    'device_series_exit' => $this->log->terminal->serial_number,
                    'give_time' => now()
                ]);
            }
        }
        if ($this->isUser() && $this->isDoorKey($this->previousTerminalLog()->identification->model_type)) {
            $doorKeyLog = $this->getDoorKeyFromLog($this->previousTerminalLog()->identification->identifier_number);

            if ($this->log->terminal->mode) {
                $doorKeyLog->update([
                    'taken_identifier' => $this->log->identification->identifier_number
                ]);
            } else {
                $doorKeyLog->update([
                    'given_identifier' => $this->log->identification->identifier_number
                ]);
            }
        }
    }

    private function isDoorKey($model_type)
    {
        return IdentifierType::getType($model_type) === IdentifierType::DOOR_KEY;
    }

    private function isUser()
    {
        return IdentifierType::getType($this->log->identification->model_type) === IdentifierType::USER;
    }

    private function checkDoorKeyInLog()
    {
        return (bool)$this->getDoorKeyFromLog($this->log->identification->identifier_number);
    }

    private function getDoorKeyFromLog($identifier_number)
    {
        return DoorKeyLog::where('date', now())
            ->where('key_identifier', $identifier_number)
            ->where('device_series_enter', '!=', null)
            ->where('device_series_exit', '=', null)
            ->first();
    }


    private function createDooKeyLog()
    {
        return DoorKeyLog::create([
            'date' => now(),
            'key_identifier' => $this->log->identification->identifier_number,
            'device_series_enter' => $this->log->terminal->serial_number,
            'take_time' => now()
        ]);
    }

    public function previousTerminalLog()
    {
        return TerminalRequestLog::whereId($this->log->id - 1)->first();
    }
}
