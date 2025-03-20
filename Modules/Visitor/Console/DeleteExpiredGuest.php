<?php

namespace Modules\Visitor\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Modules\Skud\Services\TerminalConnectionService;
use Modules\Visitor\Entities\Invite;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class DeleteExpiredGuest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'delete-expired-guest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete expired guest';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(private TerminalConnectionService $terminalConnectionService)
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
        $invites = Invite::query()
            ->where('status', Invite::STATUS_EXPIRED)
            ->get();
        $this->info('Invites count - ' . count($invites));
        foreach ($invites as $invite) {
            $responses = $this->terminalConnectionService->delete($invite->guest, $invite->attachedTerminals);
            $invite->update(['status' => Invite::STATUS_MODERATED]);
            foreach ($responses as $response) {
                DB::table('guest_deleted_logs')->insert([
                    'invite_id' => $invite->id,
                    'terminal_id' => $response['terminal_id'],
                    'guest_id' => $invite->guest->id,
                    'status' => $response['status']->success,
                    'message' => $response['status']->msg,
                    'payload' => json_encode($response)
                ]);
            }

            $this->info(json_encode($responses));
        }

    }

}
