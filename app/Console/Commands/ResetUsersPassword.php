<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Skud\Entities\Company;
use Modules\User\Entities\User;

class ResetUsersPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset-users-password ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset users password for company';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $companyId = $this->ask('Ask company id');

        $newPassword = $this->ask('Password value', 'Qwerty123$');

        $company = Company::findOrFail($companyId);

        $users = User::where('company_id', '=', $company->id);
        $bar = $this->output->createProgressBar($users->count());
        $bar->start();
        $users->update(['password' => \Hash::make($newPassword)]);
        foreach ($users as $user) {
            $bar->advance();
        }
        $bar->finish();
        $this->newLine();
        $this->info('Password change finished!');
    }
}
