<?php

namespace Modules\User\Services;

use Illuminate\Support\Facades\DB;
use Modules\Skud\Services\TerminalConnectionService;
use Modules\User\Entities\User;
use Modules\User\Repositories\UserRepository;
use Modules\User\DTO\UserDTO;

class UserService
{

    public function __construct(private UserRepository $repository, private TerminalConnectionService $terminalConnectionService)
    {
    }

    public function createUser(UserDTO $userDTO): array
    {
        DB::beginTransaction();
        try {
            $user = $this->repository->create($userDTO);
            $this->repository->updateProfile($user->profile, $userDTO->profile);
            $this->syncRole($user, $userDTO->role_id);
            DB::commit();
            return ['success' => (bool)$user, 'message' => 'Пользователь создан'];
        } catch (\Exception $exception) {

            DB::rollBack();
            return ['success' => false, 'message' => $exception->getMessage()];
        }
    }

    public function updateUser(User $user, UserDTO $userDTO,): array
    {

        DB::beginTransaction();
        try {
//            $old_name = $user->getName();

            $this->repository->update($user, $userDTO);
            $this->repository->updateProfile($user->profile, $userDTO->profile);
            $this->syncRole($user, $userDTO->role_id);

//            $this->terminalConnectionService->update($user, $user->terminals, $old_name);
            DB::commit();
            return ['success' => true, 'message' => "Успешно обновлен"];
        } catch (\Exception $exception) {
            DB::rollBack();
            return ['success' => false, 'message' => $exception->getMessage()];
        }

    }

    public function updateProfile(User $user, UserDTO $userDTO): array
    {
        DB::beginTransaction();
        try {
            $old_name = $user->getName();
            $status = $this->repository->updateProfile($user->profile, $userDTO->profile);
//            $this->terminalConnectionService->update($user, $user->terminals, $old_name);
            DB::commit();
            return ['success' => true, 'message' => "Успешно обновлен"];
        } catch (\Exception $exception) {
            DB::rollBack();
            return ['success' => false, 'message' => $exception->getMessage()];
        }

    }

    public function deleteUser(User $user): array
    {
        $status = $this->repository->delete($user);

        return ['success' => $status, 'message' => 'Удалено'];
    }

    public function syncRole(User $user, $role_id)
    {
        if ($role_id) {
            $user->syncRoles($role_id);
        }
    }
}
