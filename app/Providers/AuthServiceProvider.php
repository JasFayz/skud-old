<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Modules\HR\Entities\Department;
use Modules\HR\Entities\Device;
use Modules\HR\Entities\Division;
use Modules\HR\Entities\Position;
use Modules\HR\Entities\PositionGrade;
use Modules\HR\Policies\DepartmentPolicy;
use Modules\HR\Policies\DevicePolicy;
use Modules\HR\Policies\DivisionPolicy;
use Modules\HR\Policies\PositionGradePolicy;
use Modules\HR\Policies\PositionPolicy;
use Modules\Visitor\Entities\Invite;
use Modules\Visitor\Policies\InvitePolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Invite::class => InvitePolicy::class,
        Department::class => DepartmentPolicy::class,

        PositionGrade::class => PositionGradePolicy::class,
        Position::class => PositionPolicy::class,
        Device::class => DevicePolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
