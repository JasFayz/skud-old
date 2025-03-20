<?php

namespace Modules\User\Actions\User;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Modules\User\DTO\UserDTO;
use Modules\User\Entities\User;

class CreateUserAction
{
    public function execute(UserDTO $data): Builder|Model
    {
        return User::query()->create($data->toArray());
    }
}
