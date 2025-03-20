<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Spatie\DbDumper\Compressors\GzipCompressor;
use Spatie\DbDumper\Databases\PostgreSql;

class DbDump extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:dump';

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

        $date = Carbon::now()->format('Ymd');
        $yesterday = Carbon::now()->subDay()->format('Ymd');
        $fileName = config('database.connections.pgsql.database') . '_backup_' . $date . '.gz';
        $yesterdayFileName = config('database.connections.pgsql.database') . '_backup_' . $yesterday . '.gz';
        PostgreSql::create()
            ->setHost(config('database.connections.pgsql.host'))
            ->setPort(config('database.connections.pgsql.port'))
            ->setDbName(config('database.connections.pgsql.database'))
            ->setUserName(config('database.connections.pgsql.username'))
            ->setPassword(config('database.connections.pgsql.password'))
            ->doNotCreateTables()
            ->useCompressor(new GzipCompressor())
            ->dumpToFile(storage_path('/backup/') . $fileName);
    }
}
