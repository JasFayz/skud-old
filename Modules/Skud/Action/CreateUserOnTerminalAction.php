<?php

namespace Modules\Skud\Action;

use Modules\Skud\Contracts\TerminalCreatable;
use Modules\Skud\DTOs\TerminalResponseDTO;
use Modules\Skud\Entities\Terminal;
use Modules\Skud\Services\TerminalActionLogService;

class CreateUserOnTerminalAction
{

    public function __construct(private TerminalActionLogService $terminalActionLogService)
    {
    }

    public function execute(TerminalCreatable $user, Terminal $terminal)
    {
        try {
            $response = \Http::withHeaders([
                'token' => $terminal->tokenWithAddition,
            ])->connectTimeout(3)->asJson()->post('//' . $terminal->ip . ':' . $terminal->port . '/api/devUser/add', [
                'faceUrl' => $user->getPhoto(),
                'userName' => $user->getName(),
                'userCode' => (string)$user->getIdentifier(),
                'sex' => 0,
                'userPhone' => '123456',
                'icCard' => $user->getRFSeries()
            ]);

            $content = json_decode($response->getBody()->getContents());
            $this->terminalActionLogService->handle($user, Terminal::CREATE, $terminal, $content);
            return new TerminalResponseDTO($content->success, $content->msg, $content->code);
        } catch (\Throwable $e) {
            $error = new TerminalResponseDTO(false, $e->getMessage(), $e->getCode());
            $this->terminalActionLogService->handle($user, Terminal::CREATE, $terminal, $error);
            return $error;
        }
    }
}
