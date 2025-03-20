<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Modules\HR\Entities\Department;
use Modules\HR\Entities\Position;
use Modules\Skud\Entities\Company;

class Profile extends Model
{
    protected $appends = ['photo_path', 'watchlist_photo_path'];

    protected $fillable = [
        'first_name',
        'last_name',
        'patronymic',
        'full_name',
        'user_id',
        'department_id',
        'division_id',
        'position_id',
        'photo',
        'car_number',
        'work_phone',
        'mobile_phone',
        'birthday',
        'watchlist_photo'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

//    public function getFullNameAttribute($key)
//    {
//        return $this->first_name . ' ' . $this->last_name;
//    }
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id', 'id');
    }

    public function getPhotoPathAttribute(): ?string
    {
        return $this->photo ? asset($this->photo) : null;
    }

    public function getWatchlistPhotoPathAttribute(): ?string
    {
        return $this->watchlist_photo ? asset($this->watchlist_photo) : null;
    }
}
