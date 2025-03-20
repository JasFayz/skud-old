<?php

namespace Modules\Visitor\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Modules\Skud\Contracts\iTerminalUser;
use Modules\Skud\Contracts\TerminalCreatable;
use Modules\Skud\Entities\Company;
use Modules\Skud\Entities\Terminal;
use Modules\Skud\Entities\TerminalUserIdentifier;
use Modules\Skud\Entities\Zone;
use Modules\User\Entities\User;
use Wildside\Userstamps\Userstamps;

class Guest extends Model implements TerminalCreatable
{
    use HasFactory;
    use Userstamps, SoftDeletes;

    protected $appends = ['name', 'hasCurrentInvite', 'photoUrl'];

    protected $fillable = [
        'first_name',
        'last_name',
        'patronymic',
        'full_name',
        'company_name',
        'phone_number',
        'photo',
        'company_id',
        'is_vip',
        'passport_number'
    ];


    public function identifier(): MorphOne
    {
        return $this->morphOne(TerminalUserIdentifier::class, 'identifiable', 'model_type', 'model_id');
    }

    public function getName()
    {
        return $this->full_name;
    }

    public function getIdentifier()
    {
        return $this->identifier->identifier_number;
    }

    public function getPhoto()
    {
        return $this->photo ? base64_encode(Storage::get($this->photo)) : null;
    }

    public function getRFSeries()
    {
        return;
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function scopeCompany(Builder $builder)
    {
        return $builder->when(\Auth::user()->company_id, function ($query) {
            return $query->where('company_id', '=', \Auth::user()->company_id);
        });
    }

    public function scopeOwner(Builder $builder)
    {
        return $builder->when(auth()->user()->role->grade >= 6, function ($query) {
            return $query->where('created_by', '=', \Auth::id());
        });
    }

    public function zones()
    {
        return $this->belongsToMany(Zone::class, 'guest_zones', 'guest_id', 'zone_id',)->withPivot('invite_id');
    }

    public function terminals()
    {
        return $this->belongsToMany(Terminal::class, 'guest_terminal', 'guest_id', 'terminal_id')
            ->withTimestamps();
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function hasPhoto()
    {
        return (bool)$this->photo;
    }

    public function invites()
    {
        return $this->hasMany(Invite::class, 'guest_id', 'id');
    }

    public function getNameAttribute($key)
    {
        return $this->full_name;
    }

    public function attachingTerminal($terminal_id, $invite_id)
    {
//        $this->terminals()->detach($terminal_id, ['invite_id' => $invite_id] );
        return $this->terminals()->attach($terminal_id, ['invite_id' => $invite_id]);
    }

    public function detachTerminal($terminal_id, $invite_id)
    {
        return $this->terminals()->attach($terminal_id, ['invite_id' => $invite_id]);

    }

    public function scopeWhereGuestName(Builder $builder, $guest_name)
    {
        return $builder->when($guest_name, function (Builder $builder) use ($guest_name) {
            return $builder->where('full_name', 'ilike', '%' . $guest_name . '%');
        });
    }

    public function scopeWhereCompanyName(Builder $builder, $company_name)
    {
        return $builder->when($company_name, function (Builder $builder) use ($company_name) {
            return $builder->where('company_name', 'ilike', '%' . $company_name . '%');
        });
    }

    public function scopeWherePassportNumber(Builder $builder, $passport_number)
    {
        return $builder->when($passport_number, function (Builder $builder) use ($passport_number) {
            return $builder->where('passport_number', 'ilike', '%' . $passport_number . '%');
        });
    }

    public function getHasCurrentInviteAttribute()
    {
        return !!$this->invites->filter(function ($invite) {
            return $invite->status === Invite::STATUS_APPROVED;
        })->count();
    }

    public function scopeWhereCreator(Builder $builder, ?string $creator)
    {
        return $builder->when($creator, function ($builder) use ($creator) {
            return $builder->whereHas('creator', function ($query) use ($creator) {
                return $query->where('name', 'ilike', '%' . $creator . '%');
            });
        });
    }

    public function scopeWhereCompanyId(Builder $builder, ?string $company_id): Builder
    {
        return $builder->when($company_id, function ($builder) use ($company_id) {
            return $builder->where('company_id', $company_id);
        });
    }

    public function getPhotoUrlAttribute($key): ?string
    {
        return $this->photo ? Storage::url($this->photo) : null;
    }

    public function scopeShowTrashed(Builder $builder, ?bool $only_trashed)
    {
        return $builder->when($only_trashed, function ($query) {
            $query->withTrashed();
        });
    }

    public function getUserName(): string
    {
        return '[Guest]' . $this->full_name;
    }

    public function getUserCode()
    {
        return $this->identifier->identifier_number;
    }

    public function getFaceUrl(): ?string
    {
        return $this->profile->photo ? base64_encode(Storage::get($this->profile->photo)) : null;
    }

    public function getGender()
    {
        return $this->gender;
    }

    public function getPhone()
    {
        return $this->phone;
    }
}
