<?php

namespace Modules\Skud\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\RemoteClient\Entities\Server;
use Modules\Skud\Database\factories\CompanyFactory;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = ['logo_path'];

    protected static function newFactory()
    {
        return CompanyFactory::new();
    }

    protected $fillable = ['name', 'logo', 'inn'];


    public function servers(): HasMany
    {
        return $this->hasMany(Server::class);
    }

    public function getLogoPathAttribute(): ?string
    {
        return $this->logo ? asset($this->logo) : null;
    }
}
