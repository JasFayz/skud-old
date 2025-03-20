<?php

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Skud\Entities\Terminal;
use Modules\Skud\Entities\Zone;

class TerminalTableSeeder extends Seeder
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

        Terminal::create([
            'name' => 'Device 1',
            'ip' => '192.168.20.180',
            'port' => '8080',
            'token' => '123456',
            'serial_number' => 'YGKJ20209240919',
            'mode' => true
        ]);

        Terminal::create([
            'name' => 'Device 2',
            'ip' => '192.168.20.181',
            'port' => '8080',
            'token' => '123456',
            'serial_number' => 'YGKJA2022120013',
            'mode' => false
        ]);

        Terminal::create([
            'name' => 'Device 3',
            'ip' => '192.168.8.151',
            'port' => '8080',
            'token' => '123456',
            'serial_number' => 'YGKJA2022120014',
            'mode' => true
        ]);

        Terminal::create([
            'name' => 'Device 4',
            'ip' => '192.168.8.152',
            'port' => '8080',
            'token' => '123456',
            'serial_number' => 'YGKJA2022120015',
            'mode' => false
        ]);

        $zone = Zone::create([
            'name' => 'Zone 1',
            'floor_id' => 1,
            'zone_type' => 'public',
            'is_calc_attend' => true,
        ]);

//        $zone->terminals()->sync(Terminal::pluck('id')->all());

    }
}
