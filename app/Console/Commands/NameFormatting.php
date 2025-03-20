<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\User\Entities\User;

class NameFormatting extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'formatting-user:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Start formatting  users name');

        $companyId = $this->ask('Type company id');

        DB::enableQueryLog();
        $users = User::forCompany($companyId)
            ->with(['profile'])
            ->get();
        $this->info('User quantity:' . $users->count());
        $updatedUserCount = 0;
        try {
            Db::beginTransaction();
            foreach ($users as $user) {
                if (!is_null($user->profile->full_name)) {
                    $status = $user->update([
                        'name' => \Str::upper($user->profile->full_name)
                    ]);
                    $first_name = $user->profile->first_name;
                    $last_name = $user->profile->last_name;
                    $full_name = $user->profile->full_name;
                    $user->profile()->update([
                        'first_name' => Str::upper($first_name),
                        'last_name' => Str::upper($last_name),
                        'full_name' => Str::upper($full_name)
                    ]);
                    if ($status) {
                        $updatedUserCount++;
                    }
                }
            };
            DB::commit();
        } catch (\Exception $exception) {
            $this->info('Error', $exception->getMessage());
            DB::rollBack();
            return Command::FAILURE;
        }

        $this->info('Updated User Quantity:' . $updatedUserCount);
        $this->info('Finished');
        return Command::SUCCESS;
    }
}
