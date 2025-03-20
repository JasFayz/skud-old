<?php

namespace Modules\Skud\Action;

use Illuminate\Support\Facades\Http;

class DeleteUserFromTerminalAction
{

    public function execute($terminal, $terminalUserID)
    {
        $url = '//' . $terminal->ip . ':' . $terminal->port . '/api/devUser/delete';

        $response = Http::withHeaders([
            'token' => $terminal->tokenWithAddition
        ])->connectTimeout(3)
            ->asForm()->post($url, [
                'id' => $terminalUserID
            ]);

        return json_decode($response->getBody()->getContents());
    }

}
