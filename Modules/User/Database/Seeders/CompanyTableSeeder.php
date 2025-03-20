<?php

namespace Modules\User\Database\Seeders;

use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Skud\Entities\Company;
use Modules\Skud\Entities\Floor;

class CompanyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $faker = Factory::create();
        Company::insert([
            ['name' => 'Uzinfocom', 'logo' => null, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
//            ['name' => 'Mininfocom', 'logo' => null, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]
        ]);
//        Company::factory(5)->create();
    }
}
