<?php

namespace App\Console\Commands;

use Excel;
use Illuminate\Console\Command;
use Modules\User\Imports\UsersImport;

class ImportUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Users from Excel file';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Excel::import(new UsersImport(null), public_path('users.xlsx'));
    }
}
