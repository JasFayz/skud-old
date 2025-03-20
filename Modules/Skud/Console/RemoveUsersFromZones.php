<?php

namespace Modules\Skud\Console;

use Illuminate\Console\Command;
use Modules\Skud\Action\GetTerminalInfoAction;
use Modules\Skud\Entities\Company;
use Modules\Skud\Entities\Terminal;
use Modules\Skud\Entities\Zone;
use Modules\Skud\Services\TerminalConnectionService;
use Modules\User\Entities\User;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class RemoveUsersFromZones extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'remote-from-zones:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import users to zones';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(private TerminalConnectionService $terminalConnectionService,
                                private GetTerminalInfoAction     $getTerminalInfoAction)
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
        $companyId = $this->ask('Type company id');
        $zoneInputIds = $this->ask('Type zones ids');
        $zoneIds = array_map(function ($value) {
            return (int)$value;
        }, explode(' ', $zoneInputIds));
        try {
            $company = Company::findOrFail($companyId);
            $zones = Zone::with('terminals')->whereIn('id', $zoneIds)->get();
            $terminals = Terminal::filterByZoneIds($zones->pluck('id'))
                ->where('status', Terminal::STATUS_ACTIVE)
                ->orderBy('id')
                ->get();

            foreach ($terminals as $terminal) {
                $this->info('Check terminal status...');
                $response = $this->getTerminalInfoAction->handle($terminal);
                $this->info($terminal->ip . ' - ' . ($response->success ? 'Ok' : '-'));

                if (!$response->success) {
                    $this->error('Bad response from terminal ' . $terminal->ip);
                    return;
                }
            }
            $users = User::with('profile')
                ->hasPhoto(true)
                ->where('company_id', '=', $company->id)->get();
            if ($users->count() === 0) {
                $this->error('No available users for provided company');
                return;
            }
            $this->info('Starting to remove users from terminals');
            $this->info('Users quantity - ' . $users->count());

            $bar = $this->output->createProgressBar(count($users));
            $bar->start();
            $terminalResponse = [];
            foreach ($users as $user) {
                $response = $this->terminalConnectionService->delete($user, $terminals);
                $terminalResponse[$user->id] = $response;
                $user->consoleZones()->sync($zoneIds);
                $this->info(json_encode($terminalResponse));
                $bar->advance();
            }
            $bar->finish();
            $this->info('Finish!');
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());
        }

    }

}
