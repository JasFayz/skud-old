<?php

namespace Modules\User\Interfaces;

use Modules\User\Entities\Profile;
use Modules\User\Entities\User;
use Modules\User\DTO\ProfileDTO;
use Modules\User\DTO\UserDTO;

interface UserRepositoryInterface
{
    //this is with pagination
    public function getUsers(array $user_filter);

    //this is without pagination
    public function getAllUsers(array $user_filter);

    public function getUserById($id);

    public function delete(User $user);

    public function create(UserDTO $userDTO);

    public function update(User $user, UserDTO $userDTO);

    public function updateProfile(Profile $profile, ProfileDTO $profileDTO);


}
