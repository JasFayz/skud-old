<?php

namespace Modules\Skud\Action;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Modules\Skud\Entities\Terminal;

class ChangeTerminalLogoAction
{
    public function execute(Terminal $terminal, Request $request)
    {

        $url = '//' . $terminal->ip . ':' . $terminal->port . '/api/settings/uploadLogo';

        try {
            return Http::withHeaders(['token' => $terminal->tokenWithAddition])
                ->attach('logoFile', file_get_contents($request->logo), 'image.jpg')
                ->post($url);

        } catch (\Throwable $e) {
            return false;
        }
    }
}
