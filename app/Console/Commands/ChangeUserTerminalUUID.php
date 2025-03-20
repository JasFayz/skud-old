<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Skud\Action\UpdateUserOnTerminalAction;
use Modules\Skud\Entities\Terminal;
use Modules\User\Entities\User;

class ChangeUserTerminalUUID extends Command
{

    public function __construct(private UpdateUserOnTerminalAction $updateUserOnTerminalAction)
    {
        parent::__construct();
    }

    protected $signature = 'change_user_terminal_uuid';

    protected $description = 'Change User Terminals UUID';

    public function handle()
    {
        $users = User::query()
            ->where('company_id', '=', 1)
            ->get();

        $terminals = Terminal::query()
            ->active()
            ->get();

        foreach ($terminals as $terminal) {
            foreach ($users as $user) {
                $this->updateUserOnTerminalAction->execute($user, $terminal, $user->getName());
            }
        }

    }
}
