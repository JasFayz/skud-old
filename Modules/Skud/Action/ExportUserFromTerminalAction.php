<?php

namespace Modules\Skud\Action;

use Modules\Skud\Entities\Terminal;

class ExportUserFromTerminalAction
{
    public function handle(Terminal $terminal)
    {
        try {
            $response = \Http::withHeaders([
                'token' => $terminal->tokenWithAddition,
            ])->connectTimeout(3)->post('//' . $terminal->ip . ':' . $terminal->port . '/api/devUser/export');

            return $response;
        } catch (\Throwable $e) {
            throw $e;
        }
    }
}
