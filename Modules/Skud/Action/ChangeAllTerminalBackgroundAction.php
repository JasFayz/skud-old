<?php

namespace Modules\Skud\Action;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Modules\Skud\Entities\Terminal;
use Modules\Skud\Entities\TerminalActionLog;
use Modules\User\Imports\PINFLImport;
use Throwable;

class ChangeAllTerminalBackgroundAction
{

    public function execute(Request $request)
    {
        $success_changes = [];
        $failed_changes = [];
        $terminals = Terminal::all();
        foreach ($terminals as $key => $terminal) {
            try {
                $url = '//' . $terminal->ip . ':' . $terminal->port . '/api/settings/uploadScreenSaver';
                $res = Http::withHeaders(['token' => $terminal->tokenWithAddition])
                    ->attach('screenSaverFile', file_get_contents($request->background), 'image.jpg')
                    ->post($url);
                if (json_decode($res)->msg === "OK" && json_decode($res)->code === 0) {
                    $success_changes[] = $terminal;
                } else {
                    $failed_changes[] = $terminal;
                }
            } catch (Throwable $exception) {
                $failed_changes[] = $terminal;
                continue;
            }

        }
        return ['success_changes' => $success_changes, 'failed_changes' => $failed_changes];
    }
}
