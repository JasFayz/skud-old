<?php

namespace Modules\Visitor\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Skud\Entities\Terminal;

class GuestDeletedLog extends Model
{
    use HasFactory;


    protected $fillable = [
        'invite_id',
        'terminal_id',
        'guest_id',
        'status',
        'message',
        'payload',
    ];


    public function terminal()
    {
        return $this->belongsTo(Terminal::class);
    }

    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }

    public function invite()
    {
        return $this->belongsTo(Invite::class);
    }
}
