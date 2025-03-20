<?php

namespace Modules\Skud\DTOs;

use Illuminate\Contracts\Support\Arrayable;

class TerminalResponseDTO implements Arrayable
{

    public function __construct(public bool   $success,
                                public string $msg,
                                public int    $code)
    {
    }

    public function toArray()
    {

        return [
            'success' => $this->success,
            'msg' => $this->msg,
            'code' => $this->code
        ];
    }

}
