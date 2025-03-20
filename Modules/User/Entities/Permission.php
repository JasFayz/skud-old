<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Permission extends \Spatie\Permission\Models\Permission
{
//    use HasFactory;
//
//    protected $fillable = [];
//
//    protected static function newFactory()
//    {
//        return \Modules\Admin\Database\factories\PermissionFactory::new();
//    }

    protected $fillable = [
        'name', 'label', 'permission_group_id', 'guard_name'
    ];


    public function permissionGroup(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(PermissionGroup::class);
    }
}
