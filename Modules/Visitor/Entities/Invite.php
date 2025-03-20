<?php

namespace Modules\Visitor\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Modules\Skud\Entities\Company;
use Modules\Skud\Entities\Terminal;
use Modules\User\Entities\User;
use Modules\Skud\Entities\Zone;
use Wildside\Userstamps\Userstamps;

class Invite extends Model
{
    use Userstamps, SoftDeletes;

    public const STATUS_PENDING = 0;
    public const STATUS_APPROVED = 1;
    public const STATUS_DENIED = 2;
    public const STATUS_EXPIRED = 3;
    public const STATUS_MODERATED = 4;

    protected $casts = [
        'status' => 'int'
    ];

    public static function statusList(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_APPROVED,
            self::STATUS_DENIED,
            self::STATUS_EXPIRED,
            self::STATUS_MODERATED
        ];
    }

    protected $fillable = [
        'start',
        'end',
        'responsible_user_id',
        'target_user_id',
        'comment',
        'status',
        'approved_by',
        'company_id',
        'guest_id',
        'is_editable',
        'created_by',
        'updated_by',
        'deleted_by',
        'url',
        'qr_code',
    ];

    public function zones()
    {
        return $this->belongsToMany(Zone::class, 'invite_zones', 'invite_id', 'zone_id');
    }

    public function terminals()
    {
        return $this->belongsToMany(Terminal::class, 'invite_terminal', 'invite_id', 'terminal_id');
    }

    public function attachedZones()
    {
        return $this->belongsToMany(Zone::class, 'guest_zones', 'invite_id', 'zone_id');
    }

    public function attachedTerminals()
    {
        return $this->belongsToMany(Terminal::class, 'guest_terminal', 'invite_id', 'terminal_id');

    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function guest()
    {
        return $this->belongsTo(Guest::class, 'guest_id', 'id')->withTrashed();
    }

    public function responsibleUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsible_user_id', 'id');
    }

    public function targetUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'target_user_id', 'id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeCompany(Builder $builder)
    {
        return $builder->when(Auth::user()->company_id, function ($builder) {
            return $builder->where('company_id', '=', Auth::user()->company_id);
        });
    }

    public function scopeOwner(Builder $builder)
    {
        return $builder->when(auth()->user()->role->grade >= 6, function ($query) {
            return $query->where('created_by', '=', \Auth::id());
        });
    }

    public function isApproved()
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isDenied()
    {
        return $this->status === self::STATUS_DENIED;
    }

    public function deletedLogs()
    {
        return $this->hasMany(GuestDeletedLog::class, 'invite_id', 'id');

    }

    public function scopeWhereGuestName(Builder $builder, ?string $guest_name)
    {

        return $builder->when($guest_name, function ($builder) use ($guest_name) {

            return $builder->whereHas('guest', function ($query) use ($guest_name) {
                return $query->where('full_name', 'ilike', '%' . $guest_name . '%');
            });
        });
    }

    public function scopeWherePassportNumber(Builder $builder, ?string $passport_number)
    {
        return $builder->when($passport_number, function ($builder) use ($passport_number) {
            return $builder->whereHas('guest', function ($query) use ($passport_number) {
                return $query->where('passport_number', 'ilike', '%' . $passport_number . '%');
            });
        });
    }

    public function scopeWhereCreator(Builder $builder, ?string $creator)
    {
        return $builder->when($creator, function ($builder) use ($creator) {
            return $builder->whereHas('creator', function ($query) use ($creator) {
                return $query->where('name', 'ilike', '%' . $creator . '%');
            });
        });
    }

    public function getHasPhotoAttribute($key): bool
    {
        return (bool)$this->guest->photo;
    }

    public function scopeWhereCompany(Builder $builder, ?int $company_id)
    {
        return $builder->when($company_id, function ($builder) use ($company_id) {
            return $builder->where('company_id', $company_id);
        });
    }
}
