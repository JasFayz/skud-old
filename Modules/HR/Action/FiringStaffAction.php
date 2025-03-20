<?php

namespace Modules\HR\Action;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\HR\Entities\FiredUser;
use Modules\HR\Entities\FiredUserTerminal;
use Modules\User\Entities\User;

class FiringStaffAction
{
    public function __invoke(User $user, Carbon $fired_date)
    {
        $terminals = $user->terminals;
        $fired_by = auth()->id();
        DB::beginTransaction();

        $user->update(['is_fired' => true]);
        $user->delete();
        $firedUser = FiredUser::query()->create([
            'user_id' => $user->id,
            'fired_by' => $fired_by,
            'fired_date' => $fired_date,
            'status' => User::FIRED_USER_PENDING,
            'has_terminals' => $terminals->count() > 0
        ]);

        if ($firedUser->has_terminals) {
            $firedUserTerminals = [];
            foreach ($terminals as $terminal) {
                $firedUserTerminals[] = [
                    'fired_user_id' => $firedUser->id,
                    'terminal_id' => $terminal->id,
                    'terminal_name' => $terminal->name,
                    'status' => User::FIRED_USER_PENDING,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            FiredUserTerminal::query()->insert($firedUserTerminals);
        }

        DB::commit();
    }

}
