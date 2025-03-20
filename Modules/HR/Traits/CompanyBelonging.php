<?php

namespace Modules\HR\Traits;

use Illuminate\Database\Eloquent\Builder;

trait CompanyBelonging
{
    public function scopeOwned(Builder $builder, $company_id = null)
    {
        return $builder->when($company_id ?? \Auth::user()->company_id, function ($builder) {
            return $builder->where('company_id', '=', $company_id ?? \Auth::user()->company_id);
        });
    }

}
