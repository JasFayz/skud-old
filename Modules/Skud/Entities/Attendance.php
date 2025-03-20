<?php

namespace Modules\Skud\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\User\Entities\User;

class Attendance extends Model
{
    protected $table = 'user_attendances';

    protected $fillable = [
        'user_id',
        'date',
        'came_time',
        'left_time',
        'terminal_id',
        'time_in', 'time_out'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
