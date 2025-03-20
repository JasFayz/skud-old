<?php

namespace App\Services;

interface AuthServiceContract
{
    public function getUserData(string $access_token);

    public function getAccessCredentials(string $code);
}
