<?php

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Skud\Entities\Company;
use Modules\User\Entities\Role;
use Modules\User\Entities\User;

class UserTableSeeder extends Seeder
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

        $roles = Role::query()->whereNotIn('name', ['super_admin', 'admin_security', 'security', 'user'])->get();
        $company = Company::query()->inRandomOrder()->first();
        $roles->each(function ($role) use ($company) {
            User::factory(2)->for($company)->create()->each(function ($user) use ($role) {
                $user->profile()->update([
                    'first_name' => explode(' ', $user->name)[0],
                    'last_name' => explode(' ', $user->name)[1],
                    'full_name' => explode(' ', $user->name)[1] . ' ' . explode(' ', $user->name)[0]
                ]);
                $user->assignRole($role->name);
            });
        });

        User::factory()->count(50)->for($company)->create()->each(function ($user) {
            $user->profile()->update([
                'first_name' => explode(' ', $user->name)[0],
                'last_name' => explode(' ', $user->name)[1],
                'full_name' => explode(' ', $user->name)[1] . ' ' . explode(' ', $user->name)[0]
            ]);
            $user->assignRole('user');
        });
    }
}
