<?php

namespace Modules\User\Entities;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use LaravelAndVueJS\Traits\LaravelPermissionToVueJS;
use Modules\HR\Entities\DayOff;
use Modules\HR\Entities\Department;
use Modules\HR\Entities\Device;
use Modules\HR\Entities\Position;
use Modules\HR\Entities\Watchlist;
use Modules\HR\Entities\WorkSchedule;
use Modules\Skud\Contracts\TerminalCreatable;
use Modules\Skud\Entities\Company;
use Modules\Skud\Entities\Terminal;
use Modules\Skud\Entities\TerminalUserIdentifier;
use Modules\Skud\Entities\Zone;
use Modules\User\Database\factories\UserFactory;
use Modules\User\Query\UserQueryBuilder;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements TerminalCreatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
    use LaravelPermissionToVueJS, SoftDeletes;

    public const USER_PHOTO_PATH = '/uploads/user/photos';

    public const FIRED_USER_PENDING = 0;
    public const FIRED_USER_SUCCESS = 1;
    public const FIRED_USER_FAILED = 2;


    protected static function newFactory()
    {
        return new UserFactory();
    }

    protected $appends = ['role'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'password',
        'company_id',
        'last_sess_id',
        'pinfl',
        'is_attendance',
        'is_fired',
        'hired_at',
        'in_watchlist',
        'created_by',
        'edited_by',
        'created_by_name',
        'edited_by_name',
        'uuid',
        'type',
        'telegram_payload'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'telegram_payload' => 'array'
    ];

    public function getRoleAttribute()
    {
        return $this->roles->first();
    }

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    public function zones()
    {
        return $this->belongsToMany(Zone::class, 'user_zone', 'user_id', 'zone_id')
            ->when(\Auth::user()->hasRole(['super_admin', 'admin_security']), function ($query) {
                return $query->where('zone_type', '=', 'public');
            })
            ->when(\Auth::user()->company_id, function ($query) {
                return $query->where('zone_type', '=', 'private');
            });
    }

    public function terminals()
    {
        return $this->belongsToMany(Terminal::class, 'user_terminal', 'user_id', 'terminal_id');
    }

    public function consoleZones()
    {
        return $this->belongsToMany(Zone::class, 'user_zone', 'user_id', 'zone_id');
    }

    public function scopeInitQuery(Builder $builder)
    {
        return $builder
            ->whereIsFired(false)
            ->whereHas('roles', function ($q) {
                return $q->whereNot('name', 'super_admin');
            })
            ->when(\Auth::user()?->company_id, function ($builder) {
                return $builder->where('company_id', \Auth::user()->company_id);
            });
    }

    public function scopeForUser(Builder $builder, User $user)
    {
        return $builder
            ->whereNot('id', $user->id)
            ->whereHas('roles', function ($q) {
                return $q->whereNot('name', 'super_admin');
            })
            ->when($user?->company_id, function ($builder) use ($user) {
                return $builder->where('company_id', $user->company_id);
            });
    }


    public function identifier(): MorphOne
    {
        return $this->morphOne(TerminalUserIdentifier::class, 'identifiable', 'model_type', 'model_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function devices(): HasMany
    {
        return $this->hasMany(Device::class);
    }

    public function getName(): string
    {
        return $this->profile->last_name . ' ' . $this->profile->first_name;
    }

    public function getIdentifier()
    {
        return $this->identifier->identifier_number;
    }

    public function getPhoto(): ?string
    {
        return $this->profile->photo ? base64_encode(Storage::get($this->profile->photo)) : null;
    }

    public function getRFSeries()
    {
        return;
    }

    public function schedules()
    {
        return $this->hasMany(WorkSchedule::class)->orderBy('day');
    }

    public function newEloquentBuilder($query)
    {
        return new UserQueryBuilder($query);
    }

    public function scopeFilterByFio(Builder $builder, $filterData)
    {
        return $builder->when($filterData, function ($query) use ($filterData) {
            return $query->whereHas('profile', function ($q) use ($filterData) {
                return $q->where('first_name', 'ilike', '%' . $filterData . '%')
                    ->orWhere('last_name', 'ilike', '%' . $filterData . '%');
            });
        });
    }

    public function scopeFilterByRole(Builder $builder, $filterData)
    {
        $builder->when($filterData, function ($builder) use ($filterData) {
            return $builder->whereHas('roles', function ($query) use ($filterData) {
                return $query->where('id', '=', $filterData);
            });
        });
    }

    public function scopeHasPhoto(Builder $builder, bool $has_photo)
    {
        return $builder->when($has_photo, function ($subBuilder) use ($has_photo) {
            return $subBuilder->whereHas('profile', function ($query) {
                return $query->where('photo', '!=', null);
            });
        });
    }

    public function scopeFilterByCompany(Builder $builder, $company_id)
    {
        return $builder->when($company_id, function ($builder) use ($company_id) {
            return $builder->where('company_id', '=', $company_id);
        });
    }

    public function scopeFilterByDepartment(Builder $query, $department_id)
    {
        if (is_null($department_id)) {
            return $query;
        }
        return $query->whereHas('profile', function ($q) use ($department_id) {
            return $q->where('department_id', '=', $department_id);
        });
    }

    public function scopeForChief(Builder $builder)
    {
        return $builder->when(auth()->user()->hasRole('chief'), function ($builder) {
            return $builder->whereHas('profile', function ($query) {
                $profile = auth()->user()->profile;
                $departmentIds = [$profile->department_id, ...$profile->department->descendants()->pluck('id')];
                return $query->whereIn('department_id', $departmentIds);
            });
        });
    }

    public function attachingTerminal($terminal_id)
    {
        $this->terminals()->detach($terminal_id);
        return $this->terminals()->attach($terminal_id);
    }

    public function dayOffs()
    {
        return $this->hasMany(DayOff::class)->orderBy('from', 'desc');
    }

    public function scopeShouldBeAttend(Builder $builder)
    {
        return $builder->where('is_attendance', true);
    }

    public function watchlist()
    {
        return $this->hasMany(Watchlist::class, 'user_id', 'id');
    }

    public function isLocal(): bool
    {
        return $this->type === 'local';
    }

    public function telegrams(): HasMany
    {
        return $this->hasMany(TelegramUser::class, 'user_id');
    }

}
