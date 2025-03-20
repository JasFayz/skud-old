<?php

namespace Modules\HR\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\HR\Traits\CompanyBelonging;
use Modules\User\Entities\User;

class Device extends Model
{
    use SoftDeletes, CompanyBelonging;

    protected $fillable = ['type', 'trademark', 'serial_number', 'extra_info', 'comment', 'company_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
