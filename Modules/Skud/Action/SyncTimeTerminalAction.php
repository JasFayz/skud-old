<?php

namespace Modules\Skud\Action;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Modules\Skud\DTOs\TerminalResponseDTO;
use Modules\Skud\Entities\Terminal;
use Modules\Skud\Services\TerminalActionLogService;

class SyncTimeTerminalAction
{

    public function execute(Terminal $terminal, Carbon $date)
    {
        try {
            $response = \Http::withHeaders([
                'token' => $terminal->tokenWithAddition,
            ])->connectTimeout(3)->asForm()->post('//' . $terminal->ip . ':' . $terminal->port . '/api/settings/updateDateTime', [
                'dateTime' => $date->timezone('Asia/Tashkent')->format('Y-m-d H:i:s'),
            ]);
            $content = json_decode($response->getBody()->getContents());

            return $content;
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return new TerminalResponseDTO(false, 'Connection timeout', 500);
        } catch (\Throwable $e) {
            $error = new \stdClass();
            $error->success = false;
            $error->msg = $e->getMessage();
            $error->code = $e->getCode();
            return $error;
        }

    }
}
