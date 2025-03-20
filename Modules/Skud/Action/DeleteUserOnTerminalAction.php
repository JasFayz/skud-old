<?php

namespace Modules\Skud\Action;

use GuzzleHttp\Client;
use Modules\Skud\Contracts\TerminalCreatable;
use Modules\Skud\DTOs\TerminalResponseDTO;
use Modules\Skud\Entities\Terminal;
use Modules\Skud\Services\TerminalActionLogService;


class DeleteUserOnTerminalAction
{
    public function __construct(
        public Client                        $client,
        private GetTerminalUserListAction    $getTerminalUserListAction,
        private DeleteUserFromTerminalAction $deleteUserFromTerminalAction,
        private TerminalActionLogService     $terminalActionLogService
    )
    {

    }

    public function execute(TerminalCreatable $user, Terminal $terminal)
    {
        try {
            $requestData = $this->checkUserInDeviceList($user, $terminal);

            if ($requestData) {
                $response = $this->sendToDelete($requestData, $terminal);

                $this->terminalActionLogService->handle($user, Terminal::DELETE, $terminal, $response);

                return new TerminalResponseDTO($response->success, $response->msg, $response->code);
            } else {
                return new TerminalResponseDTO(false, "User not founded or terminal didn't answer", 0);
            }

        } catch (\Throwable $e) {
            $error = new  TerminalResponseDTO(false, $e->getMessage(), $e->getCode());

            $this->terminalActionLogService->handle($user, Terminal::DELETE, $terminal, $error);

            return $error;
        }

    }

    protected function checkUserInDeviceList(TerminalCreatable $user, Terminal $terminal)
    {
        try {

            $responseContent = $this->getTerminalUserListAction->execute($terminal, 1, $user->getName());

            \Log::channel('terminal-action')->info('Check User in Device', [
                'user_id' => $user->id,
                'user_name' => $user->getName(),
                'terminal_ip' => $terminal->ip,
                'identifier_number' => $user->getIdentifier(),
                'response' => $responseContent->data->data,
                'issetData' => gettype($responseContent->data->data)
            ]);
            if (isset($responseContent->data->data)) {
                foreach ($responseContent->data->data as $terminalMember) {
                    \Log::channel('terminal_action')->info('Compare user identifier', [
                        'terminal_user_code' => $terminalMember->userCode,
                        'terminal_user_id' => $terminalMember->id,
                        'user_identifier' => $user->getIdentifier(),
                    ]);
                    if ($terminalMember->userCode == $user->getIdentifier()) {
                        return $requestData = [
                            'id' => $terminalMember->id
                        ];
                    }
                }
            }

        } catch (\Exception $exception) {
            \Log::channel('terminal-action')->info("Error", [
                'message' => $exception->getMessage()
            ]);
            throw $exception;
        }
    }

    protected function sendToDelete($requestData, Terminal $terminal)
    {
        try {
            $responseContent = $this->deleteUserFromTerminalAction->execute($terminal, $requestData['id']);

            if ($responseContent->code === 500) {
                throw new \Exception(__('Не удалось удалить пользователя'));
            }

            return $responseContent;
        } catch (\Throwable $e) {
            \Log::channel('terminal-action')->error('Error', [
                'message' => $e->getMessage()
            ]);
        }
    }


}
