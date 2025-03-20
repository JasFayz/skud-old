<?php

namespace Modules\Skud\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Skud\QueryBuilder\ZoneQueryBuilder;

class Zone extends Model
{
    use HasFactory;

    const TYPE_PUBLIC = 'public';
    const TYPE_PRIVATE = 'private';

    protected $fillable = ['name', 'floor_id', 'is_calc_attend', 'zone_type', 'company_id'];

    public function floor(): BelongsTo
    {
        return $this->belongsTo(Floor::class);
    }

//    public function terminals(): BelongsToMany
//    {
//        return $this->belongsToMany(Terminal::class, 'terminal_zone', 'zone_id', 'terminal_id')
//            ->where('status', '=', Terminal::STATUS_ACTIVE);
//    }

    public function terminals()
    {
        return $this->hasMany(Terminal::class)
            ->where('status', '=', Terminal::STATUS_ACTIVE)
            ->where('terminal_type', '=', 'local');;
    }

    public function activeTerminals()
    {
        return $this->hasMany(Terminal::class, 'zone_id', 'id')
            ->where('status', '=', Terminal::STATUS_ACTIVE)
            ->where('terminal_type', '=', 'local');
    }

    public function children()
    {
        return $this->hasMany(Terminal::class);
    }

    public function scopeInitQuery(Builder $builder)
    {
        return $builder->when(auth()->user()->company_id && !auth()->user()->hasRole('top_invite_manager'), function ($builder) {
            return $builder->with('floor')->whereHas('floor', function ($q) {
                return $q->where('company_id', auth()->user()->company_id);
            });
        });


//        if (\Auth::user()->role->name === 'admin_company')
//            return $builder->where('zone_type', '=', 'private')
//                ->with('floor')
//                ->whereHas('floor', function ($query) {
//                    return $query->company_id === \Auth::user()->profile->company_id;
//                });
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function scopeWithFloor(Builder $builder): Builder
    {
        return $builder->with(['floor', 'floor.company']);
    }

    public function scopeWithTerminals(Builder $builder): Builder
    {
        return $builder->with('terminals');
    }

    public function scopeByType(Builder $builder, $zone_type)
    {
        return $builder->when($zone_type, function ($builder) use ($zone_type) {
            return $builder->where('zone_type', '=', $zone_type);
        });
    }

    public function scopeWithCompany(Builder $builder)
    {
        return $builder->with('company');
    }

    public function newEloquentBuilder($query)
    {
        return new ZoneQueryBuilder($query);
    }


}
