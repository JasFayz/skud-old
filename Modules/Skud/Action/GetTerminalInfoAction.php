<?php


namespace Modules\Skud\Action;


use Modules\Skud\DTOs\TerminalResponseDTO;
use Modules\Skud\Entities\Terminal;

class GetTerminalInfoAction
{

    public function handle(Terminal $terminal)
    {
        try {
            $response = \Http::withHeaders([
                'token' => $terminal->tokenWithAddition,
            ])->connectTimeout(2)
                ->get('//' . $terminal->ip . ':' . $terminal->port . '/api/aboutDevice/loadInfo');

            $responseStatus = json_decode($response->getBody()->getContents());

            return $responseStatus;
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return new TerminalResponseDTO(false, 'Connection timeout', 500);
        } catch (\Throwable $e) {
            return new TerminalResponseDTO(false, $e->getMessage(), $e->getCode());
        }
    }
}
