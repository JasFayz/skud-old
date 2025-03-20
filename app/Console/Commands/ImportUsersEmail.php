<?php

namespace App\Console\Commands;

use App\Imports\UsersEmailImport;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use Modules\User\Entities\User;

class ImportUsersEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:users-email';

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

        Excel::import(new UsersEmailImport, public_path('users.xlsx'));

    }
}
