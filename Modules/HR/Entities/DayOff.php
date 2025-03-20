<?php

namespace Modules\HR\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\User\Entities\User;

class DayOff extends Model
{
    use HasFactory;

    protected $appends = ['type_symbol'];

    protected $fillable = [
        'user_id',
        'type',
        'from',
        'to',
        'comment',
    ];

    protected static function newFactory()
    {
        return \Modules\Management\Database\factories\DayOffFactory::new();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getTypeSymbolAttribute($key)
    {
        return DayOffType::getSymbol($this->type);
    }


}
