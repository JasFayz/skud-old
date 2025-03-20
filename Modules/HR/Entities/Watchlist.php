<?php

namespace Modules\HR\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Watchlist extends Model
{
    use HasFactory;

    protected $table = 'watchlist';
    protected $fillable = [
        'id',
        'user_id',
        'target_user_id'
    ];


    public function getAttribute($key)
    {

    }
}
