<?php

namespace Modules\HR\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\ResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\HR\Action\FiringStaffAction;
use Modules\HR\Entities\Device;
use Modules\HR\Http\Requests\CreateStaffRequest;
use Modules\HR\Http\Requests\UpdateStaffRequest;
use Modules\User\DTO\UserDTO;
use Modules\User\Entities\User;
use Modules\User\Services\UserService;
use Throwable;

class StaffController extends Controller
{
    public function __construct(protected UserService $userService)
    {
        $this->middleware('permission:staff.update', ['only' => ['update', 'setSchedule']]);
        $this->middleware('permission:staff.schedule', ['only' => ['setSchedule']]);
        $this->middleware('permission:staff.gadget', ['only' => ['attachDevice ', 'detachDevice']]);
    }

    public function getEmployees(Request $request)
    {
        $filter = [
            'fio' => $request->query('fio'),
            'has_photo' => $request->query('has_photo'),
            'pinfl' => $request->query('pinfl')
        ];

        return User::initQuery()
            ->with(['company', 'zones', 'terminals',
                'terminals.zone', 'roles', 'profile',
                'profile.company', 'schedules', 'devices',
                'profile.position',
                'profile.department'])
            ->whereFio($filter['fio'])
            ->wherePinfl($filter['pinfl'])
            ->orderBy('name')
            ->paginate();
    }

    public function getAllEmployees()
    {
        return User::initQuery()
            ->with(['company', 'zones', 'roles', 'profile', 'profile.company', 'identifier', 'schedules', 'devices'])
            ->orderBy('name')
            ->get();
    }


    public function store(CreateStaffRequest $request)
    {
        try {
            $userDTO = UserDTO::fromCreateRequest($request);
            $response = $this->userService->createUser($userDTO);
            return response()->json($response);
        } catch (\Exception $exception) {
            return ['success' => false, 'message' => $exception->getMessage()];
        }
    }

    public function update(User $user, UpdateStaffRequest $request)
    {
        try {
            $old_name = $user->getName();

            $user->update([
                'name' => $request->get('last_name') . " " . $request->get('first_name'),
                'is_attendance' => filter_var($request->get('is_attendance'), FILTER_VALIDATE_BOOLEAN),
                'edited_by' => Auth::id(),
                'edited_by_name' => Auth::user()->getName(),
                'pinfl' => $request->get('pinfl'),
            ]);
            $this->userService->syncRole($user, $request->get('role_id'));
            $profile = $this->userService->updateProfile($user, UserDTO::fromUpdateRequest($request));

            return $profile;
        } catch (\Exception $exception) {
            return ['success' => false, 'message' => $exception->getMessage()];
        }
    }

    public function attachDevice(Request $request)
    {
        try {
            Device::whereIn('id', $request->devices)?->update([
                'user_id' => $request->user_id
            ]);

            return ['success' => true, 'message' => 'Успешно прикреплен'];
        } catch (Throwable $exception) {
            return ['success' => false];
        }
    }

    public function detachDevice(Request $request)
    {
        try {
            Device::where('id', $request->id)->update([
                'user_id' => null
            ]);
            return ['success' => true];
        } catch (Throwable $exception) {
            return ['success' => false];
        }
    }

    public function setSchedule(User $user, Request $request)
    {
        $requestData = $request->post();

        foreach ($requestData as $schedule) {
            $user->schedules()->updateOrCreate(
                ['day' => $schedule['day']],
                [
                    'day' => $schedule['day'],
                    'is_active' => $schedule['is_active'],
                    'start' => $schedule['start'],
                    'end' => $schedule['end'],
                    'minutes' => $schedule['minutes']
                ]);
        }
        return ['success' => true, 'message' => 'Successfully set schedule'];
    }

    public function firingUser(User $user, Request $request, FiringStaffAction $firingUserAction)
    {
        $firingDate = $request->get('firingDate');

        ($firingUserAction)($user, Carbon::make($firingDate));

        if (Carbon::parse($firingDate)->isToday()) {
            \Artisan::call('firing:staff');
        }

        return ResponseHelper::success([], 'Пользователь будет удален ' . $firingDate);
    }


    public function addToWatchList(Request $request)
    {
        $validate = $request->validate([
            'users' => 'required|array|min:1'
        ]);

        DB::beginTransaction();
        $request->user()->watchlist()->delete();

        foreach ($validate['users'] as $target_user) {
            $request->user()->watchlist()->updateOrCreate([
                'user_id' => $request->user()->id,
                'target_user_id' => $target_user
            ], [
                'user_id' => $request->user()->id,
                'target_user_id' => $target_user
            ]);
        }
        DB::commit();
        return ['message' => 'Добавлены', 'success' => true];

    }

    public function removeFromWatchList(User $user)
    {
        Auth::user()->watchlist()->where('target_user_id', $user->id)->first()->delete();

        return ['message' => 'Удален из наблюдения', 'success' => true];
    }
}
