<?php

namespace App\Console\Commands;

use App\Imports\PositionImport;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use Modules\User\Imports\UsersImport;

class ImportPosition extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:positions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Position from Excel file';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Excel::import(new PositionImport(), public_path('users.xlsx'));
    }
}
