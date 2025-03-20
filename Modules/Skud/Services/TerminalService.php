<?php

namespace Modules\Skud\Services;

use Illuminate\Support\Facades\Log;
use Modules\Skud\Entities\IdentifierType;
use Modules\Skud\Entities\Terminal;
use Modules\Skud\Entities\TerminalRequestLog;

class TerminalService
{


    public function __construct(
        protected AttendanceCalculationService $attendanceCalculationService,
        protected DoorKeyManageService         $doorKeyManageService
    )
    {
    }

    public function storeLog($requestData)
    {
        try {
            foreach (json_decode($requestData['data']) as $data) {
                if ($data->userCode) {
                    $terminal = Terminal::where('serial_number', '=', $data->devSn)
                        ->active()
                        ->firstOrFail();
                    if (!$terminal) {
                        Log::build([
                            'driver' => 'single',
                            'path' => storage_path('logs/no-terminal.log'),
                        ])->info([
                            'dev' => $data->devSn
                        ]);
                    }


                    $log = TerminalRequestLog::create([
                        'date' => now(),
                        'terminal_date' => $data->createTime,
                        'terminal_id' => $terminal->id,
                        'terminal_mode' => $terminal->mode,
                        'identifier_number' => $data->userCode,
                        'photo' => $data->faceImageBase64 ?? null,
                        'is_calc_attend' => $terminal?->zone?->is_calc_attend
                    ]);

                    Log::build([
                        'driver' => 'single',
                        'path' => storage_path('logs/terminal.log'),
                    ])->info($log);


//                    $this->doorKeyManageService->handle($log);
//
//                    if ($terminal->zone()->is_calc_attend) {
//                        $this->attendanceCalculationService->call($log);
//                    }
                }
            }
            return (['success' => true]);
        } catch (\Exception $exception) {
            Log::build([
                'driver' => 'single',
                'path' => storage_path('logs/terminal-push.log'),
            ])->info(json_encode($requestData));
            Log::info($exception->getMessage());
            return (['success' => false, 'message' => $exception->getMessage()]);
        }

    }

}
