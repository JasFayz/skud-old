<?php

namespace Modules\Skud\Action;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Modules\Skud\Entities\Terminal;
use Modules\Skud\Contracts\TerminalCreatable;

class CheckTerminalAction
{
    public function execute(TerminalCreatable $user, Terminal $terminal)
    {
        $skipThisDevice = false;
        $client = new Client();

        $url = '//' . $terminal->ip . ':' . $terminal->port . '/api/devUser/listData';

//        $response = $client->request('POST', $url, [
//            'json' => [
//                'userName' => $user->getName()
//            ],
//            'headers' => [
//                'token' => $terminal->tokenWithAddition,
//                'pageNum' => 1,
//                'pageSize' => 30
//            ],
//            'connect_timeout' => 5
//        ]);
        try {
            $response = Http::withHeaders([
                'token' => $terminal->tokenWithAddition,
                'pageNum' => 1,
                'pageSize' => 30
            ])->retry(3, 200, throw: false)
                ->timeout(1)
                ->post($url, [
                    'userName' => $user->getName()
                ]);

            $responseContent = $response->getBody()->getContents();

            if (isset($responseContent->data->data)) {
                foreach ($responseContent->data->data as $terminalUser) {
                    if ($terminalUser->userCode == $user->getIdentifier()) {
                        $skipThisDevice = true;
                    }
                }
            }

            return $skipThisDevice;
        } catch (\Throwable $e) {
            return false;
        }


        //Не нужно отправлять пользователя если он уже есть в устройстве. Переходим к следующему устройству.

    }
}
