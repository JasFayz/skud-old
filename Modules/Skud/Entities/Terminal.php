<?php

namespace Modules\Skud\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Skud\QueryBuilder\TerminalQueryBuilder;

class Terminal extends Model
{
    use SoftDeletes;

    public const CREATE = 'create';
    public const UPDATE = 'update';
    public const DELETE = 'delete';
    public const SYNC = 'sync';
    public const INFO = 'info';

    public const STATUS_ACTIVE = 1;
    public const STATUS_INACTIVE = 0;

    public const MODE_IN = 1;
    public const MODE_OUT = 0;

    public function statusesList()
    {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_INACTIVE => 'Inactive'
        ];
    }

    protected $appends = ['tokenWithAddition'];
    protected $fillable = [
        'name',
        'floor_id',
        'ip',
        'port',
        'mode',
        'token',
        'serial_number',
        'status',
        'company_id',
        'terminal_type',
        'remote_zone_type',
        'remote_terminal_id',
        'server_uuid',
        'remote_zone_id',
        'remote_terminal_id'
    ];


//    protected $hidden = ['token'];

    public function getTokenWithAdditionAttribute($key)
    {
        return md5($this->token . 'YT');
    }

//    public function zones(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
//    {
//        return $this->belongsToMany(Zone::class, 'terminal_zone', 'terminal_id', 'zone_id');
//    }

    public function zone()
    {
        return $this->belongsTo(Zone::class, 'zone_id');
    }


//    public function zone()
//    {
//        return $this->zones->first();
//    }


    public function scopeFilterByZoneIds(Builder $builder, $zoneIds)
    {
        return $builder->whereIn('zone_id', $zoneIds);

    }

    public function newEloquentBuilder($query)
    {
        return new TerminalQueryBuilder($query);
    }

    public function scopeActive(Builder $builder)
    {
        return $builder->where('status', '=', Terminal::STATUS_ACTIVE);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

}
