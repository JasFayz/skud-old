<?php

namespace Modules\User\Repositories;

use Modules\User\Entities\Profile;
use Modules\User\Entities\User;
use Modules\User\Interfaces\UserRepositoryInterface;
use Modules\User\DTO\ProfileDTO;
use Modules\User\DTO\UserDTO;

class UserRepository implements UserRepositoryInterface
{
    public function getUsers(array $filter)
    {

        $users = User::query()
            ->with(['company', 'roles', 'profile', 'zones', 'zones.terminals'])
            ->forUser(\Auth::user())
            ->forRole($filter['role'])
            ->forCompany($filter['company_id'])
            ->whereFio($filter['fio'])
            ->hasPhoto((bool)filter_var($filter['has_photo'], FILTER_VALIDATE_BOOLEAN))
            ->latest()
            ->paginate(20);

        return $users;

    }

    public function getAllUsers(array $user_filter)
    {
        return User::initQuery()
            ->with(['company', 'roles', 'profile', 'profile.company', 'identifier'])
            ->filterByDepartment($user_filter['department_id'])
//            ->filterByDivision($user_filter['division_id'])
            ->get();

    }

    public function getUserById($id)
    {
        return User::findOrFail($id);
    }

    public function create(UserDTO $userDTO)
    {
        return $user = User::create([
            'name' => $userDTO->name,
            'email' => $userDTO->email,
            'company_id' => $userDTO->company_id,
            'password' => \Hash::make('Qwerty123$'),
            'created_by' => $userDTO->created_by,
            'created_by_name' => $userDTO->created_by_name,
            'pinfl' => $userDTO->pinfl
        ]);
    }

    public function update(User $user, UserDTO $userDTO): bool
    {
        return $user->update([
            'company_id' => $userDTO->company_id,
            'email' => $userDTO->email,
            'edited_by' => $userDTO->edited_by,
            'edited_by_name' => $userDTO->edited_by_name,
            'pinfl' => $userDTO->pinfl
        ]);
    }

    public function delete(User $user)
    {
        return $user->delete();
    }

    public function updateProfile(Profile $profile, ProfileDTO $profileDTO): bool
    {

        return $profile->update([
            'first_name' => $profileDTO->first_name,
            'last_name' => $profileDTO->last_name,
            'patronymic' => $profileDTO->patronymic,
            'full_name' => $profileDTO->last_name . ' ' . $profileDTO->first_name . ' ' . $profileDTO->patronymic,
            'photo' => $profileDTO->photo,
            'department_id' => $profileDTO->department_id,
            'position_id' => $profileDTO->position_id,
            'mobile_phone' => $profileDTO->mobile_phone,
            'work_phone' => $profileDTO->work_phone,
            'car_number' => $profileDTO->car_number,
            'birthDay' => $profileDTO->birthday,
            'watchlist_photo' => $profileDTO->watchlist_photo
        ]);

    }
}
