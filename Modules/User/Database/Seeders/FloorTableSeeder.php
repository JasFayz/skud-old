<?php

namespace Modules\User\Database\Seeders;

use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Skud\Entities\Company;
use Modules\Skud\Entities\Floor;

class FloorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // $this->call("OthersTableSeeder");
        $companyIds = Company::query()->get()->pluck('id');
        $faker = Factory::create();

        $companyId = $faker->randomElement($companyIds);
        DB::table('floors')->insert([
            ['name' => '1 Floor', 'label' => '01', 'company_id' => $companyId, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => '2 Floor', 'label' => '02', 'company_id' => $companyId, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
    }
}
