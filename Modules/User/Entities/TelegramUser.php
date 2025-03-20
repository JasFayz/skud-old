<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TelegramUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'telegram_id',
        'username',
        'hash',
        'auth_date',
        'first_name',
        'last_name',
        'photo_url',
    ];


}
