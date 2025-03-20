<?php

namespace Modules\HR\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Meeting extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'date', 'description', 'start', 'end', 'user_id'];
}
