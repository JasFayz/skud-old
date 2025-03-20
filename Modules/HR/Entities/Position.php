<?php

namespace Modules\HR\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\HR\Traits\CompanyBelonging;


class Position extends Model
{
    use HasFactory, CompanyBelonging;

    protected $fillable = ['name', 'division_id', 'company_id', 'grade_id'];

//    protected static function newFactory()
//    {
//        return \Modules\Management\Database\factories\PositionFactory::new();
//    }


//    public function division()
//    {
//        return $this->belongsTo(Division::class);
//    }

    public function grade()
    {
        return $this->belongsTo(PositionGrade::class);
    }

//    public function scopeFilterByCompany(Builder $builder, $company_id = null)
//    {
//        return $builder->when($company_id ?? \Auth::user()->company_id, function ($builder) {
//            return $builder->where('company_id', '=', $company_id ?? \Auth::user()->company_id);
//        });
//
//    }

    public function positionGrand(Builder $builder)
    {
        return $builder->whereHas('grade', function ($query) {
            return $query->where('number', '>=', auth()->user()->profile->position?->grade?->id);
        });
    }
}
