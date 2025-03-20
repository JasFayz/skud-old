<?php

namespace Modules\Skud\Action;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Modules\Skud\Entities\Terminal;

class GetTerminalUserListAction
{
    public function __construct(public Client $client)
    {
    }


    public function execute(Terminal $terminal, $page = 1, ?string $usernama = "")
    {
        $url = '//' . $terminal->ip . ':' . $terminal->port . '/api/devUser/listData';

        $response = Http::withHeaders([
            'token' => $terminal->tokenWithAddition,
            'pageNum' => (int)$page,
            'pageSize' => 30
        ])->connectTimeout(3)->post($url, [
            'userName' => $usernama
        ]);

        return json_decode($response->getBody()->getContents());
    }
}
