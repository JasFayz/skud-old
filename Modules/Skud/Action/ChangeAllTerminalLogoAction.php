<?php

namespace Modules\Skud\Action;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Modules\Skud\Entities\Terminal;

class ChangeAllTerminalLogoAction
{
    public function execute(Request $request)
    {
        $success_changes = [];
        $failed_changes = [];
        $terminals = Terminal::all();
        foreach ($terminals as $key => $terminal) {
            try {
                $url = '//' . $terminal->ip . ':' . $terminal->port . '/api/settings/uploadLogo';
                $res = Http::withHeaders(['token' => $terminal->tokenWithAddition])
                    ->attach('logoFile', file_get_contents($request->logo), 'image.jpg')
                    ->post($url);
                if (json_decode($res)->msg === "OK" && json_decode($res)->code === 0) {
                    $success_changes[] = $terminal;
                } else {
                    $failed_changes[] = $terminal;
                }
            } catch (\Throwable $e) {
                $failed_changes[] = $terminal;
                continue;
            }
        }
        return ['success_changes' => $success_changes, 'failed_changes' => $failed_changes];
    }
}
