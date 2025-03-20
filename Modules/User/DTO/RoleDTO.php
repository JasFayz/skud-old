<?php

namespace Modules\User\DTO;

use Illuminate\Http\Request;
use Spatie\LaravelData\Data;

class RoleDTO extends Data
{
    public function __construct(
        public string $name,
        public string $label,
        public int    $grade,
        public array  $permissions,
        public int    $team_id
    )
    {
    }

    public static function fromRequest(Request $request)
    {
        return new self(
            trim($request->get('name')),
            trim($request->get('label')),
            trim($request->get('grade')),
            $request->get('permissions'),
            $request->get('team_id')
        );
    }
}
