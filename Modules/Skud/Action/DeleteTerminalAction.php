<?php

namespace Modules\Skud\Action;

use GuzzleHttp\Client;
use Modules\Skud\Entities\Terminal;
use Modules\Skud\Contracts\TerminalCreatable;
use Modules\Skud\Services\TerminalActionLogService;


class DeleteTerminalAction
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

        $requestData = $this->checkUserInDeviceList($user, $terminal);

        if ($requestData) {
            try {
                $response = $this->sendToDelete($requestData, $terminal);
                $this->terminalActionLogService->handle($user, Terminal::DELETE, $terminal, $response);

                return $response;

            } catch (\Throwable $e) {
                $error = new \stdClass();
                $error->success = false;
                $error->msg = $e->getMessage();
                $error->code = $e->getCode();
                $this->terminalActionLogService->handle($user, Terminal::DELETE, $terminal, $error);
                return $error;
            }
        }
        return ['message' => 'User not found'];
    }

    protected function checkUserInDeviceList(TerminalCreatable $user, Terminal $terminal)
    {

        try {
            $responseContent = $this->getTerminalUserListAction->execute($terminal, 1, $user->getName());

            if (isset($responseContent->data->data)) {
                foreach ($responseContent->data->data as $terminalMember) {
                    if ($terminalMember->userCode == $user->getIdentifier()) {
                        return $requestData = [
                            'id' => $terminalMember->id
                        ];
                    }
                }
            }

        } catch (\Exception $exception) {
//            throw $exception;
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

        }
    }


}
