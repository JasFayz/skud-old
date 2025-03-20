<?php

namespace Modules\Skud\Action;

use GuzzleHttp\Client;
use Modules\Skud\Entities\Terminal;
use Modules\Skud\Contracts\TerminalCreatable;

class UpdateTerminalAction
{

    public function __construct(public Client $client)
    {
    }

    public function execute(TerminalCreatable $user, Terminal $terminal, $old_name)
    {
        try {
            $requestData = $this->prepareRequestForUserUpdate($user, $terminal, $old_name);
            return $this->sendToUpdate($requestData, $terminal);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    private function prepareRequestForUserUpdate(TerminalCreatable $user, Terminal $terminal, $old_name)
    {

        try {
            $response = $this->client->request('POST', '//' . $terminal->ip . ':' . $terminal->port . '/api/devUser/listData', [
                'json' => [
                    'userName' => $old_name
                ],
                'headers' => [
                    'token' => $terminal->tokenWithAddition,
                    'pageNum' => 1,
                    'pageSize' => 30
                ],
                'connect_timeout' => 5
            ]);

            $responseContent = json_decode($response->getBody()->getContents());

            if (!isset($responseContent->data->data)) {
                return $this->prepareRequestForUserUpdate($user, $terminal, $old_name);
            }

            foreach ($responseContent->data->data as $terminalMember) {
                if ($terminalMember->userCode == $user->getIdentifier()) {
                    return $requestData = [
                        'id' => $terminalMember->id,
                        'faceUrl' => $user->getPhoto(),
                        'userName' => $user->getName(),
                        'userCode' => (string)$user->getIdentifier(),
                        'sex' => 0, //0=male, 1=female,
                        'userPhone' => '12345',
                        'faceToken' => $terminalMember->faceToken,
                        'faceEnable' => $terminalMember->faceEnable,
//                        'icCard' => $user->rfid_serial
                    ];
                }
            }

//        if (!isset($requestData)) {
//            event(new MemberCreatedEvent($member));
//            return $this->prepareRequestForUserUpdate($member, $device, $old_fullName);
//        }


        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function sendToUpdate($requestData, $device)
    {
        try {
            $response = $this->client->request('POST', '//' . $device->ip_address . ':' . $device->port . '/api/devUser/saveEdit', [
                'json' => $requestData,
                'headers' => [
                    'token' => $device->tokenWithAddition,
                ],
                'connect_timeout' => 5
            ]);
            $responseContent = json_decode($response->getBody()->getContents());

            if ($responseContent->code === 500) {
                throw new \Exception(__('Загрузите другое изображение. С этим что-то не так.'));
            }
            return $responseContent;

        } catch (\Exception $exception) {
            throw $exception;
        }

    }
}
