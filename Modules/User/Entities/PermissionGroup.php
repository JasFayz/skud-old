<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PermissionGroup extends Model
{

    protected $fillable = ['name', 'slug', 'team_id'];


    public function permissions(): HasMany
    {
        return $this->hasMany(Permission::class);
    }


}
