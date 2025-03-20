<?php

namespace App\Http\Controllers\Auth;


use Illuminate\Contracts\Auth\Authenticatable;

class AuthRedirectHelper
{
    public const ADMIN = '/admin/user';
    public const USER = '/profile';

    public static function handle(Authenticatable $user)
    {

        if (in_array($user?->role?->name, ['admin_security', 'super_admin'])) {
            return AuthRedirectHelper::ADMIN;
        }

        return AuthRedirectHelper::USER;
    }
}
