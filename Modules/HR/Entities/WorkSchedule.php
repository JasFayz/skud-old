<?php

namespace Modules\HR\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkSchedule extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'start', 'end', 'day', 'minutes', 'is_active'];


}
