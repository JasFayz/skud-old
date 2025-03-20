<?php

namespace Modules\Skud\DTOs;

use Modules\Skud\Http\Requests\TerminalRequest;
use Spatie\LaravelData\Data;

class TerminalDTO extends Data
{

    public function __construct(
        public string $name,
        public bool   $mode,
        public string $ip,
        public string $port,
        public string $token,
        public string $serial_number,
        public int    $status
    )
    {
    }

    public static function fromRequest(TerminalRequest $request)
    {
        return new self(
            trim($request->get('name')),
            $request->get('mode'),
            trim($request->get('ip')),
            trim($request->get('port')),
            trim($request->get('token')),
            trim($request->get('serial_number')),
            $request->get('status')
        );
    }
}
