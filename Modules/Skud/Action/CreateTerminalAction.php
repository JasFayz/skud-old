<?php

namespace Modules\Skud\Action;

use GuzzleHttp\Client;
use Modules\Skud\Entities\Terminal;
use Modules\Skud\Contracts\TerminalCreatable;
use Modules\Skud\Services\TerminalActionLogService;

class CreateTerminalAction
{

    public function __construct(private TerminalActionLogService $terminalActionLogService)
    {
    }

    public function execute(TerminalCreatable $user, Terminal $terminal)
    {
        try {
            $response = \Http::withHeaders([
                'token' => $terminal->tokenWithAddition,
            ])->asJson()->post('//' . $terminal->ip . ':' . $terminal->port . '/api/devUser/add', [
                'faceUrl' => $user->getPhoto(),
                'userName' => $user->getName(),
                'userCode' => (string)$user->getIdentifier(),
                'sex' => 0,
                'userPhone' => '123456',
                'icCard' => $user->getRFSeries()
            ]);

            $content = json_decode($response->getBody()->getContents());
            $this->terminalActionLogService->handle($user, Terminal::CREATE, $terminal, $content);
            return $content;
        } catch (\Throwable $e) {
            $error = new \stdClass();
            $error->success = false;
            $error->msg = $e->getMessage();
            $error->code = $e->getCode();
            $this->terminalActionLogService->handle($user, Terminal::CREATE, $terminal, $error);
            return $error;
        }

    }
}
