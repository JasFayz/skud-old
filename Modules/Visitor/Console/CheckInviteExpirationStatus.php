<?php

namespace Modules\Visitor\Console;

use Illuminate\Console\Command;
use Modules\Visitor\Entities\Invite;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CheckInviteExpirationStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'check-invite-expiration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check invite expiration time';

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
    public function handle()
    {
        $this->info('Run Check Invite Expiration');
        $invites = Invite::query()
            ->where('status', '=', Invite::STATUS_APPROVED)
            ->where('end', '<', now())
            ->update(['status' => Invite::STATUS_EXPIRED]);
        $this->info('Finish Check Expiration');
        $this->info('Changed model count - '. $invites);
    }

}
