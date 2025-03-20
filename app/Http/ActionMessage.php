<?php

namespace App\Http;

class ActionMessage
{
    public function __construct(
        public array|object $data = [],
        public string       $message = "",
        public bool         $success
    )
    {
    }
}
