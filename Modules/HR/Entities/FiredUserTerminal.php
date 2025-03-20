<?php

namespace Modules\HR\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Skud\Entities\Terminal;

class FiredUserTerminal extends Model
{
    use HasFactory;

    protected $fillable = [
        'fired_user_id',
        'terminal_id',
        'terminal_name',
        'status',
        'action_status',
        'message',
    ];

    protected $table = 'fired_user_terminal';


    public function terminal()
    {
        return $this->belongsTo(Terminal::class, 'terminal_id', 'id');
    }

}
