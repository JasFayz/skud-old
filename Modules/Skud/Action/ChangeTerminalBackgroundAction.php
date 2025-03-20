<?php

namespace Modules\Skud\Action;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Modules\Skud\Entities\Terminal;

class ChangeTerminalBackgroundAction
{
    public function execute(Terminal $terminal, Request $request)
    {
        $url = '//' . $terminal->ip . ':' . $terminal->port . '/api/settings/uploadScreenSaver';
        try {
            return Http::withHeaders(['token' => $terminal->tokenWithAddition])
                ->attach('screenSaverFile', file_get_contents($request->background), 'image.jpg')
                ->post($url);

        } catch (\Throwable $e) {
            return false;
        }

    }
}
