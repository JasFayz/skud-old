<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends \Spatie\Permission\Models\Role
{
//    use HasFactory;
//
//    protected $fillable = [];
//
//    protected static function newFactory()
//    {
//        return \Modules\Admin\Database\factories\RoleFactory::new();
//    }
    const TEAM_ADMIN = 1;
    const TEAM_USER = 2;
    protected $fillable = ['name', 'label', 'guard_name', 'grade', 'team_id'];

    public function scopeInitQuery()
    {
        return $this
            ->where('name', '!=', 'super_admin')
            ->where('grade', '>=', \Auth::user()->roles()->first()->grade);
    }

    public function scopeForUser(Builder $builder, $user)
    {
        return $builder
            ->where('grade', '>', 0)
            ->where('grade', '>=', $user->roles()->first()->grade);
    }

    public static function list()
    {
        return [
            self::TEAM_ADMIN => 'Admin',
            self::TEAM_USER => 'User'
        ];
    }
}
