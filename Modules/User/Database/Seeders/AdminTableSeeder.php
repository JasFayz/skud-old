<?php

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\User\Entities\Role;
use Modules\User\Entities\User;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $super_admin = User::create([
            'name' => 'Jasur Akhmatfaezov',
            'email' => 'jasfayz@gmail.com',
            'password' => \Hash::make('password')
        ]);
        $super_admin->profile()->update([
            'first_name' => 'Jasur',
            'last_name' => 'Akhmatfaezov'
        ]);
        $super_admin->assignRole(Role::where('name', 'super_admin')->first());
        $admin = User::create([
            'name' => 'Security Admin',
            'email' => 'admin_security@mail.com',
            'password' => \Hash::make('password'),
        ]);
        $super_admin->profile()->update([
            'first_name' => 'Security',
            'last_name' => 'Admin'
        ]);
        $admin->assignRole('admin_security');
    }
}
