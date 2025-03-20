<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\User\Entities\User;

class FillUsersUuidCommand extends Command
{

    protected $signature = 'fill-user-uuid';


    protected $description = 'Fill user uuid';

    public function handle()
    {
        User::query()
            ->with(['identifier'])
            ->chunk(20, function ($users) {
                foreach ($users as $user) {
                    if (!$user->uuid) {
                        $user->update([
                            'uuid' => $user->identifier->identifier_number
                        ]);
                    }
                }
            });
    }
}
