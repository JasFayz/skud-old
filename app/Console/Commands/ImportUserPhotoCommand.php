<?php

namespace App\Console\Commands;

use App\Imports\UserPhotoImport;
use Excel;
use Illuminate\Console\Command;
use Modules\User\Imports\UsersImport;

class ImportUserPhotoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:user-photo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import User photo';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $company_id = $this->ask('Type Company Id');
        if (!$company_id) {
            $this->info('Company id is empty');
            return Command::INVALID;
        }
        Excel::import(new UserPhotoImport($company_id), storage_path('user_photo.xlsx'));

        $this->info('Finished');
        return Command::SUCCESS;
    }
}
