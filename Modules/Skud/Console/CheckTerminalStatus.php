<?php

namespace Modules\Skud\Console;

use App\Notifications\TerminalStatusProblemNotification;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Modules\Skud\Action\GetTerminalInfoAction;
use Modules\Skud\Entities\Terminal;
use Notification;
use NotificationChannels\Telegram\TelegramUpdates;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CheckTerminalStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'check-terminal-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check terminals connection status';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(protected GetTerminalInfoAction $getTerminalInfoAction)
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
        $terminals = Terminal::query()->active()->get();


        $updates = TelegramUpdates::create()
            // (Optional). Get's the latest update. NOTE: All previous updates will be forgotten using this method.
            // ->latest()
            // (Optional). Limit to 2 updates (By default, updates starting with the earliest unconfirmed update are returned).
            // (Optional). Add more params to the request.

            ->get();

        if ($updates['ok']) {
            // Chat ID
            $chatId = $updates['result'][0]['message']['chat']['id'];
        }

        foreach ($terminals as $terminal) {
            $response = $this->getTerminalInfoAction->handle($terminal);

            \DB::table('terminals_status_logs')->updateOrInsert([
                'terminal_id' => $terminal->id
            ], [
                'terminal_id' => $terminal->id,
                'success' => $response->success,
                'message' => $response->msg,
            ]);
            $statusLog = \DB::table('terminals_status_logs', 'tsl')->where('terminal_id', $terminal->id)
                ->join('terminals as t', 't.id', 'tsl.terminal_id',)
                ->first();

            if (!$statusLog->success) {
                Notification::route('telegram', $chatId)
                    ->notify(new TerminalStatusProblemNotification($statusLog));
            }
        }

    }


}
