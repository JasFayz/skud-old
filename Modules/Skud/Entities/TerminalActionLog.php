<?php

namespace Modules\Skud\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class TerminalActionLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'terminal_id',
        'action_type',
        'user_id',
        'identifier_number',
        'status',
        'payload',
        'response',
    ];


    public function terminal()
    {
        return $this->belongsTo(Terminal::class)
            ->withTrashed();
    }

    public function identification()
    {
        return $this->belongsTo(TerminalUserIdentifier::class, 'identifier_number', 'identifier_number');
    }
}
