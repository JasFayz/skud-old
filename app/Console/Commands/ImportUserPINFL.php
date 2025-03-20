<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use Modules\User\Imports\PINFLImport;

class ImportUserPINFL extends Command
{
    protected $signature = 'import:pinfl';

    protected $description = "Import PINFLs to users table.";

    public function handle()
    {
        $file_name = $this->ask('Enter file name');

        if (!\File::exists(storage_path($file_name . '.xlsx'))) {
            $this->error('Can\'t find file');
            return Command::FAILURE;
        }

        Excel::import(new PINFLImport(), storage_path($file_name . '.xlsx'));

        $this->info('Finished');
    }
}
