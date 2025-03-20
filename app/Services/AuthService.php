<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\RequestException;
use http\Exception\InvalidArgumentException;
use Illuminate\Support\Str;

class AuthService implements AuthServiceContract
{
    private string $auth_url;
    private string $client_id;
    private string $client_secret;
    private string $scope;

    public function __construct(protected Client $client)
    {
        $this->auth_url = config('oauth.egov.auth_url');
        $this->client_id = config('oauth.egov.client_id');
        $this->client_secret = config('oauth.egov.client_secret');
        $this->scope = config('oauth.egov.scope');
    }


    public function getUserData(string $access_token)
    {
        if (!$access_token) {
            throw new InvalidArgumentException('Invalid token');
        }
        $response = $this->client->post($this->auth_url, [
            'form_params' => [
                'grant_type' => 'one_access_token_identify',
                'client_id' => $this->client_id,
                'client_secret' => $this->client_secret,
                'access_token' => $access_token,
            ]
        ]);

        return json_decode($response->getBody()->getContents());
    }

    public function getAccessCredentials(string $code)
    {
        try {
            $response = $this->client->post($this->auth_url, [
                'form_params' => [
                    'grant_type' => 'one_authorization_code',
                    'client_id' => $this->client_id,
                    'client_secret' => $this->client_secret,
                    'code' => $code,
                ]
            ]);

            $result = json_decode($response->getBody()->getContents());

            if (is_null($result)) {
                throw new BadResponseException('Returned result is null', '', $response);
            }
            return $result;

        } catch (RequestException $exception) {
            throw $exception;
        }
    }

    public function getLoginRedirectQueryURL(): string
    {
        $params = http_build_query([
            'client_id' => $this->client_id,
            'redirect_uri' => route('login.callback'),
            'response_type' => 'one_code',
            'scope' => $this->scope,
            'state' => Str::random(32),
        ]);
        return config('oauth.egov.auth_url') . "?$params";
    }
}
