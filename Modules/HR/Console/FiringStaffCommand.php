<?php

namespace Modules\HR\Console;

use DB;
use Illuminate\Console\Command;
use Modules\HR\Entities\FiredUser;
use Modules\Skud\Entities\Terminal;
use Modules\Skud\Services\TerminalConnectionService;
use Modules\User\Entities\User;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class FiringStaffCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'firing:staff';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(protected TerminalConnectionService $terminalConnectionService)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Start firing staffs');
        $firedUsers = FiredUser::query()
            ->where('fired_date', '<=', now())
            ->whereIn('status', [User::FIRED_USER_PENDING, User::FIRED_USER_FAILED])
            ->with(['firedUserTerminals', 'firedUserTerminals.terminal'])
            ->get();
        $this->info('Firing Staff quantity: ' . $firedUsers->count());
        try {
            $terminalResponse = [];
            foreach ($firedUsers as $firedUser) {
                $terminalIds = $firedUser->firedUserTerminals->pluck('terminal_id');
                $terminals = Terminal::query()->whereIn('id', $terminalIds)->get();
                if ($terminals->count() > 0) {
                    $terminalResponse[$firedUser->id] = $this->terminalConnectionService->delete($firedUser->user, $terminals);
                }
            }

            if (count($terminalResponse) > 0) {
                foreach ($terminalResponse as $id => $response) {
                    $firedUser = FiredUser::query()->find($id);
                    $firedUser->update([
                        'status' => User::FIRED_USER_SUCCESS,
                        'fired_at' => now()
                    ]);
                    foreach ($response as $res) {
                        if (!$res['status']->success) {
                            $firedUser->update(['status' => User::FIRED_USER_FAILED]);
                        }
                        $firedUser->firedUserTerminals()->whereTerminalId($res['terminal_id'])->update([
                            'action_status' => $res['status']->success,
                            'message' => $res['status']->msg,
                            'status' => $res['status']->success ? User::FIRED_USER_SUCCESS : User::FIRED_USER_FAILED
                        ]);
                    }
                }
            }

            $this->info('Successfully finished');
        } catch (\Exception $exception) {
            $this->error('Something went wrong');
            $this->error($exception->getMessage());

            return Command::FAILURE;
        }
        return Command::SUCCESS;
    }

}


