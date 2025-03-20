<?php

namespace Modules\HR\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\User\Entities\User;

class FiredUser extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'fired_user';

    protected $fillable = [
        'user_id',
        'fired_by',
        'fired_at',
        'fired_date',
        'status',
        'has_terminals'
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')
            ->withTrashed();

    }

    public function firedUserTerminals()
    {
        return $this->hasMany(FiredUserTerminal::class, 'fired_user_id', 'id')
            ->whereIn('status', [User::FIRED_USER_PENDING, User::FIRED_USER_FAILED]);
    }

}
