<?php

namespace Modules\Skud\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TerminalUserIdentifier extends Model
{
    use HasUuids;

    protected $fillable = ['identifier_number'];


    public function uniqueIds()
    {
        return ['identifier_number'];
    }

    public function identifiable(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'model_type', 'model_id')
            ->withTrashed();
    }

    public static function generateIdentifier()
    {
        return \Str::uuid(8) . '_' . Carbon::now()->timestamp;
    }


}
