<?php

namespace Modules\Skud\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;
use Modules\Skud\Entities\Terminal;
use Modules\Skud\Entities\TerminalUserIdentifier;
use Modules\User\Entities\User;
use Modules\Visitor\Entities\Guest;

class TerminalRequestLog extends Model
{

    protected $appends = ['temporary_file_path'];

    protected $fillable = [
        'date',
        'terminal_date',
        'terminal_id',
        'terminal_mode',
        'identifier_number',
        'is_calc_attend',
        'photo',
        'type',
        'remote_terminal_request_log_id',
        'server_uuid'
    ];

    protected $hidden = [
        'file_path'
    ];

    public function terminal()
    {
        return $this->belongsTo(Terminal::class)
            ->withTrashed();
    }

    public function identification()
    {
        return $this->belongsTo(TerminalUserIdentifier::class, 'identifier_number', 'identifier_number');
    }

    public function scopeFilterByTerminalId(Builder $builder, ?string $terminal_id)
    {
        return $builder->when($terminal_id, function ($query) use ($terminal_id) {
            return $query->where('terminal_id', '=', $terminal_id);
        });
    }

    public function scopeFilterByDate(Builder $builder, ?string $date)
    {
        return $builder->when($date, function ($query) use ($date) {
            return $query->whereDate('date', $date);
        });
    }

    public function scopeFilterByFio(Builder $builder, ?string $fio)
    {
        return $builder->when($fio, function (Builder $builder) use ($fio) {
            return $builder->whereHas('identification', function (Builder $query) use ($fio) {
                $query->whereHasMorph('identifiable', [User::class], function ($query) use ($fio) {
                    $query->where('name', 'ilike', '%' . $fio . '%');
                })->orWhereHasMorph('identifiable', [Guest::class], function ($query) use ($fio) {
                    $query->where('full_name', 'ilike', '%' . $fio . '%');
                });
            });
        });
    }

    public function scopeWherePinfl(Builder $builder, string $pinfl)
    {
        return $builder->whereHas('identification', function (Builder $query) use ($pinfl) {
            $query->whereHasMorph('identifiable', [User::class], function ($query) use ($pinfl) {
                $query->where('pinfl', '=', $pinfl);
            });
        });
    }

    public function getTemporaryFilePathAttribute()
    {
        return $this->file_path ? \Storage::disk(env('TERMINAL_BASE64_STORAGE_DISK'))
            ->temporaryUrl( $this->file_path, now()->addMinutes(5)) : null;
    }
}



