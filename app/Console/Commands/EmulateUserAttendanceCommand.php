<?php

namespace App\Console\Commands;


use Illuminate\Console\Command;
use Modules\Skud\Entities\Terminal;
use Modules\Skud\Entities\TerminalRequestLog;
use Modules\Skud\Services\AttendanceCalculationService;
use Modules\User\Entities\User;

class EmulateUserAttendanceCommand extends Command
{

    public function __construct(private AttendanceCalculationService $attendanceCalculationService)
    {
        parent::__construct();
    }

    protected $signature = 'emulate:user-attendance';

    protected $description = 'Command description';

    public function handle()
    {

        $userId = $this->ask('Enter user id');
        $user = User::findOrFail($userId);
        $mode = $this->choice('Choose mode', ['Exit', 'Enter']);

        $enterTerminal = Terminal::where('mode', '=', true)->active()->first();
        $exitTerminal = Terminal::where('mode', '=', false)->active()->first();


        $date = $this->ask('Type date ', now()->format('Y-m-d'));
        $time = $this->ask('Type time', now()->format('H:i:s'));
        $terminal = $mode === 'Enter' ? $enterTerminal : $exitTerminal;

        $terminalLog = TerminalRequestLog::create([
            'date' => $date . ' ' . $time,
            'terminal_date' => $date . ' ' . $time,
            'terminal_id' => $terminal->id,
            'terminal_mode' => $terminal->mode,
            'identifier_number' => $user->identifier->identifier_number,
            'is_calc_attend' => $terminal->zone->is_calc_attend,
            'photo' => null
        ]);

//        $this->attendanceCalculationService->handle($terminalLog);
    }
}
