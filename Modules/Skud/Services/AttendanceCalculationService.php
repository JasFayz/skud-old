<?php

namespace Modules\Skud\Services;


use Carbon\Carbon;
use Modules\Skud\Entities\Attendance;
use Modules\Skud\Entities\Terminal;
use Modules\Skud\Entities\TerminalRequestLog;
use Modules\User\Entities\User;

class AttendanceCalculationService
{
    private TerminalRequestLog $terminalLog;
    private Terminal $terminal;
    private User $user;
    private Carbon $date;


    protected function register(TerminalRequestLog $terminalLog)
    {
        $log = $terminalLog->load(['terminal', 'identification', 'identification.identifiable']);
        $this->terminalLog = $log;
        $this->terminal = $log->terminal;
        $this->user = $log->identification->identifiable;
        $this->date = Carbon::parse($log->date);
    }

    private function formattingDate(): string
    {
        return Carbon::parse($this->date)->format('Y-m-d');
    }

    public function handle(TerminalRequestLog $terminalLog)
    {
        $this->register($terminalLog);

        if (!$this->checkUserAttendanceExists()) {
            if ($this->terminal->mode) {
                $this->createAttendance();
            } else {
                $this->createCloseAttendance();
            }
        } else {
            if (!$this->terminal->mode) {
                if ($this->lastCame()) {
                    $this->lastCame()->update([
                        'left_time' => $this->date,
                        'time_in' => Carbon::parse($this->lastCame()->came_time)->diffInMinutes($this->date)
                    ]);
                } else {
                    $this->createCloseAttendance();
                }
            } else {
                $this->createAttendance();
            }

        }
    }

    private function checkUserAttendanceExists()
    {
        return Attendance::where('user_id', '=', $this->user->id)
            ->where('date', '=', $this->formattingDate())
            ->exists();
    }

    private function createAttendance()
    {
        Attendance::create([
            'user_id' => $this->user->id,
            'terminal_id' => $this->terminal->id,
            'date' => $this->formattingDate(),
            'came_time' => $this->date
        ]);
    }

    private function createCloseAttendance()
    {
        Attendance::create([
            'user_id' => $this->user->id,
            'terminal_id' => $this->terminal->id,
            'date' => $this->formattingDate(),
            'came_time' => $this->date,
            'left_time' => $this->date
        ]);
    }

    private function lastCame()
    {
        return Attendance::where('user_id', '=', $this->user->id)
            ->where('date', '=', $this->formattingDate())
            ->where('came_time', '<>', null)
            ->latest()
            ->first();
    }

    private function lastLeft()
    {
        return Attendance::where('user_id', '=', $this->user->id)
            ->where('date', '=', $this->formattingDate())
            ->where('came_time', '<>', null)
            ->where('left_time', '<>', null)
            ->latest()
            ->first();
    }

}
