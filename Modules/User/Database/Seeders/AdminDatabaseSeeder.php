<?php

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class AdminDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(CompanyTableSeeder::class);
        $this->call(FloorTableSeeder::class);
        $this->call(PermissionTableSeeder::class);
        $this->call(AdminTableSeeder::class);
//        $this->call(UserTableSeeder::class);
        $this->call(TerminalTableSeeder::class);
    }
}
