<?php

namespace Modules\Skud\Console;

use Illuminate\Console\Command;
use Modules\Skud\Action\GetTerminalInfoAction;
use Modules\Skud\Action\SyncTimeTerminalAction;
use Modules\Skud\Entities\Terminal;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SyncTerminalTime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'sync-terminal-time';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize terminal time';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(SyncTimeTerminalAction $syncTimeTerminalAction, GetTerminalInfoAction $getTerminalInfoAction)
    {
        $terminals = Terminal::query()
            ->active()
            ->where('terminal_type', 'local')
            ->get();

        $this->info('Start sync time');
        $date = now();
        foreach ($terminals as $terminal) {
            $this->info('Check terminal is online - ' . $terminal->ip);
            $response = $getTerminalInfoAction->handle($terminal);
            $this->info($terminal->ip . ' - ' . ($response->success ? 'Ok' : 'Err ' . $response->msg));
            $this->info('Sync Time to terminal ' . $terminal->ip);
            $syncResponse = $syncTimeTerminalAction->execute($terminal, $date);
            $this->info('Date: ' . $date->timezone('Asia/Tashkent')->format('Y-m-d H:i:s'));
            $this->info($syncResponse->msg);
        }
    }


}
