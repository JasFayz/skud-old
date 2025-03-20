<?php

namespace Modules\HR\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Kalnoy\Nestedset\NodeTrait;
use Modules\HR\Traits\CompanyBelonging;
use Modules\User\Entities\Profile;
use Modules\User\Entities\User;

class Department extends Model
{
    use HasFactory, CompanyBelonging, NodeTrait;

    protected $fillable = ['name', 'chief_id', 'company_id'];


    public function children()
    {
        return $this->hasMany(get_class($this), $this->getParentIdName())
            ->setModel($this)->with(['children', 'positions']);
    }

    protected function getScopeAttributes()
    {
        return ['company_id'];
    }

    public function positions()
    {
        return $this->belongsToMany(Position::class, 'department_positions');
    }

    public function users()
    {
        return $this->hasManyThrough(User::class, Profile::class);
    }

    public static function fixOwnedTree()
    {
        return self::scoped(['company_id' => auth()->user()->company_id])->fixTree();
    }
}
