<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Modules\User\Database\Seeders\PermissionTableSeeder;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $seeder = PermissionTableSeeder::class;

}
