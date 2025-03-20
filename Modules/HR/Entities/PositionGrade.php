<?php

namespace Modules\HR\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\HR\Traits\CompanyBelonging;

class PositionGrade extends Model
{
    use SoftDeletes, CompanyBelonging;

    protected $fillable = [
        'name', 'number', 'company_id'
    ];

    public function positions(): HasMany
    {
        return $this->hasMany(Position::class);
    }
//
//    public function scopeOwnedByCompany(Builder $builder, $company_id = null)
//    {
//        return $builder->when($company_id ?? \Auth::user()->company_id, function ($builder) {
//            return $builder->where('company_id', '=', $company_id ?? \Auth::user()->company_id);
//        });
//    }
}
