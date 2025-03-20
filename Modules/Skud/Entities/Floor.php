<?php

namespace Modules\Skud\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Skud\Database\factories\FloorFactory;

class Floor extends Model
{
    use HasFactory;

    protected static function newFactory(): FloorFactory
    {
        return FloorFactory::new();
    }

    protected $fillable = ['name', 'label', 'company_id'];


    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
