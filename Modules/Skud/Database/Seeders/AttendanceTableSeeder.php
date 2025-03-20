<?php

namespace Modules\Skud\Database\Seeders;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Skud\Entities\Terminal;
use Modules\Skud\Entities\TerminalRequestLog;
use Modules\Skud\Services\AttendanceCalculationService;
use Modules\User\Entities\User;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;


class AttendanceTableSeeder extends Seeder
{
    private $enterTerminal;
    private $exitTerminal;


    public function __construct(private AttendanceCalculationService $attendanceCalculationService)
    {

    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $days = CarbonPeriod::create(Carbon::now()->subMonths(6)->startOfMonth(), Carbon::now())->filter('isWeekday');
        $users = User::with('roles', 'identifier')->whereHas('roles', function ($query) {
            return $query->where('name', '=', 'user');
        })->get();

        $this->enterTerminal = Terminal::with('zones')->where('mode', true)
            ->whereHas('zones', function ($q) {
                return $q->where('is_calc_attend', true);
            })
            ->active()
            ->inRandomOrder()->first();
        $this->exitTerminal = Terminal::with('zones')->where('mode', false)
            ->whereHas('zones', function ($q) {
                return $q->where('is_calc_attend', true);
            })
            ->active()
            ->inRandomOrder()->first();

        $output = new ConsoleOutput();
        $amount = count($days) * $users->count();

        $progressBar = new ProgressBar($output, $amount);
        $progressBar->start();
        foreach ($days as $day) {
            foreach ($users as $user) {
                if (rand(1, $users->count()) == $user->id) {
                    continue;
                }
                if ($day->format('Y-m-d') != now()->format('Y-m-d')) {
                    $this->firstCame($day, $user)->toJson();
                    $this->lunchLeft($day, $user)->toJson();
                    $this->lunchCame($day, $user)->toJson();
                    $this->lastLeft($day, $user)->toJson();
                    $progressBar->advance();
                }
            }
        }
        $progressBar->finish();
        $output->write(' Finish', true);
    }


    protected function firstCame($day, $user)
    {
        $enterDate = Carbon::parse($day)->setTime(rand(8, 9), rand(1, 59));

        return $logEnter = TerminalRequestLog::create([
            'date' => $enterDate,
            'terminal_date' => $enterDate,
            'terminal_id' => $this->enterTerminal->id,
            'terminal_mode' => $this->enterTerminal->mode,
            'identifier_number' => $user->identifier->identifier_number,
            'is_calc_attend' => $this->enterTerminal->zone()->is_calc_attend,
            'photo' => null
        ]);
    }

    protected function lunchLeft($day, $user)
    {
        $exitDate = Carbon::parse($day)->setTime(rand(12, 12), rand(30, 59));


        return $logExit = TerminalRequestLog::create([
            'date' => $exitDate,
            'terminal_date' => $exitDate,
            'terminal_id' => $this->exitTerminal->id,
            'terminal_mode' => $this->exitTerminal->mode,
            'identifier_number' => $user->identifier->identifier_number,
            'is_calc_attend' => $this->exitTerminal->zone()->is_calc_attend,
            'photo' => null
        ]);
    }

    protected function lunchCame($day, $user)
    {
        $enterDate = Carbon::parse($day)->setTime(rand(13, 14), rand(0, 59));

        return $logEnter = TerminalRequestLog::create([
            'date' => $enterDate,
            'terminal_date' => $enterDate,
            'terminal_id' => $this->enterTerminal->id,
            'terminal_mode' => $this->enterTerminal->mode,
            'identifier_number' => $user->identifier->identifier_number,
            'is_calc_attend' => $this->enterTerminal->zone()->is_calc_attend,
            'photo' => null
        ]);
    }

    protected function lastLeft($day, $user)
    {
        $exitDate = Carbon::parse($day)->setTime(rand(17, 19), rand(1, 59));

        return $logExit = TerminalRequestLog::create([
            'date' => $exitDate,
            'terminal_date' => $exitDate,
            'terminal_id' => $this->exitTerminal->id,
            'terminal_mode' => $this->exitTerminal->mode,
            'identifier_number' => $user->identifier->identifier_number,
            'is_calc_attend' => $this->exitTerminal->zone()->is_calc_attend,
            'photo' => null
        ]);
    }
}
