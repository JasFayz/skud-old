<?php

namespace Modules\User\Http\Controllers\API;

use App\Exports\ExportUser;
use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Modules\User\Actions\User\ImportUserPhotosActions;
use Modules\User\DTO\UserDTO;
use Modules\User\Entities\User;
use Modules\User\FilterData\UserFilterData;
use Modules\User\Http\Requests\CreateUserRequest;
use Modules\User\Http\Requests\UpdateUserRequest;
use Modules\User\Imports\UsersImport;
use Modules\User\Repositories\UserRepository;
use Modules\User\Services\UserService;
use Modules\User\ViewModels\UserViewModel;
use ZipArchive;

class UserController extends Controller
{

    public function __construct(public UserService $userService, protected UserRepository $repository)
    {
        $this->middleware('permission:user.create', ['only' => 'store']);
        $this->middleware('permission:user.update', ['only' => 'update']);
        $this->middleware('permission:user.delete', ['only' => 'delete']);
        $this->middleware('role:super_admin', ['only' => 'loginAs']);
    }

    public function getUsers(Request $request)
    {
        $filter = new UserFilterData(
            fio: $request->query('fio'),
            has_photo: $request->query('has_photo'),
            role: $request->query('role'),
            company_id: $request->query('company_id'),
        );

        $userView = new UserViewModel(auth()->user(), $filter);

        return $userView->getIndex();
    }

    public function getAll(Request $request)
    {
        $filter = new UserFilterData(
            fio: $request->query('fio'),
            department_id: $request->query('department_id')
        );

        $userView = new UserViewModel(auth()->user(), $filter);

        return $userView->getAll();
    }

    public function store(CreateUserRequest $request)
    {
        $response = $this->userService->createUser(UserDTO::fromCreateRequest($request));

        return response()->json($response);
    }

    public function update(User $user, UpdateUserRequest $request)
    {
        $response = $this->userService->updateUser($user, UserDTO::fromUpdateRequest($request));

        return response()->json($response);
    }

    public function destroy(User $user)
    {
        $response = $this->userService->deleteUser($user);

        return response()->json($response);
    }

    public function loginAs(User $user)
    {
        return Auth::guard('web')->login($user);
    }

    public function getByDepartmentIds(Request $request)
    {
        if (!$request->query('department_ids')) {
            return [];
        }
        return User::query()->whereHas('profile', function ($q) use ($request) {
            return $q->whereIn('department_id', $request->query('department_ids'));
        })->get();
    }

    public function export()
    {
        ini_set('memory_limit', '-1');

        $maxAncestorCount = 0;

        $users = User::with(['profile', 'roles', 'profile.department', 'profile.department.ancestors', 'profile.position'])
            ->join('profiles', 'profiles.user_id', '=', 'users.id')
            ->where('users.company_id', '=', auth()->user()->company_id)
            ->orderBy('profiles.department_id')
            ->get()->each(function ($user) use (&$maxAncestorCount) {
                if ($user->profile?->department?->ancestors->count() > $maxAncestorCount) {
                    $maxAncestorCount = $user->profile->department->ancestors->count();
                }
            });
//            dd($users->toArray());
        return Excel::download(new ExportUser(auth()->user()->company_id), strtolower(auth()->user()->company->name) . '_' . Str::slug(now()->toDateString()) . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);

        return view('user::export-staff', ['users' => $users, 'maxAncestorCount' => $maxAncestorCount]);
    }

    public function import(Request $request, ImportUserPhotosActions $importUserPhotosActions)
    {
        $validate = $request->validate([
            'company_id' => 'required|numeric',
            'file' => 'required|file|mimes:xlsx,xls',
            'photos' => 'nullable',
            'photos.*' => 'image|mimes:jpeg,png,jpg|max:10000'
        ]);
        (new UsersImport($request->get('company_id')))->import($request->file('file'));

        if ($request->hasFile('photos')) {
            $importUserPhotosActions->execute($validate['photos'], $validate['company_id']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Данные импортированы'
        ]);
    }

    public function refreshPassword(Request $request)
    {
        $validate = $request->validate([
            'id' => 'required|numeric',
            'newPassword' => 'required|string|min:8'
        ]);

        $user = User::find($request->id);
        $user->password = bcrypt($request->newPassword);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Успешно'
        ]);

    }


    public function exportUsersImages(Request $request)
    {
        $users = User::query()
            ->with(['company', 'profile'])->hasPhoto(true)->get();
        $files = [];

        foreach ($users as $user) {
            $files[$user->company->name][$user->name] = $user->profile->photo;
        }

        $data = Carbon::now()->format('Y-d-m');

        $zip_file = 'export_photos_' . $data . '.zip';
        $zip_file_path = storage_path($zip_file);
//        dd( phpinfo());
        $zip = new ZipArchive();

        $zip->open($zip_file_path, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        foreach ($files as $company => $users) {
            $zip->addEmptyDir($company);
            foreach ($users as $name => $path) {
                if ($path && \Illuminate\Support\Facades\File::exists(storage_path('app/' . $path))) {
                    $file = \Illuminate\Support\Facades\File::get(storage_path('app/' . $path));
                    $zip->addFromString($company . '/' . $name . '.jpg', $file);
                }
            }
        }
        $zip->close();
        $headers = [
            'Content-Type' => 'application/octet-stream',
            'File-Name' => $zip_file,
        ];
        $file = \Illuminate\Support\Facades\File::get($zip_file_path);

        return response()->streamDownload(function () use ($file) {
            echo $file;
        }, $zip_file, $headers);
        return response()->download($zip_file, $zip_file, $headers);
    }

    public function telegramCallback(Request $request): Redirector|Application|RedirectResponse
    {
        $check_hash = $request->hash;
        $requestArray = $request->all();
        $string = collect($requestArray)
            ->except('hash')
            ->filter()
            ->map(function ($value, $key) {
                return sprintf('%s=%s', $key, $value);
            })
            ->values()
            ->sort()
            ->implode("\n");

        $secret_key = hash('sha256', config('services.telegram-bot-api.token'), true);
        $hash = hash_hmac('sha256', $string, $secret_key);

        if (strcmp($hash, $check_hash) !== 0) {
            throw new Exception('Data is NOT from Telegram');
        }
        if ((time() - $request->auth_date) > 86400) {
            throw new Exception('Data is outdated');
        }

        auth()->user()->telegrams()->updateOrCreate([
            'telegram_id' => $requestArray['id']
        ], [
            'telegram_id' => $requestArray['id'],
            'username' => $requestArray['username'],
            'hash' => $requestArray['hash'],
            'auth_date' => $requestArray['auth_date'],
            'first_name' => array_key_exists('first_name', $requestArray) ? $requestArray['first_name'] : null,
            'last_name' => array_key_exists('last_name', $requestArray) ? $requestArray['last_name'] : null,
            'photo_url' => array_key_exists('photo_url', $requestArray) ? $requestArray['photo_url'] : null,
        ]);

        return redirect('/profile');
    }

    public function removeTelegram(Request $request): JsonResponse
    {
        $validate = $request->validate([
            'telegram_id' => ['required']
        ]);

        $telegram = auth()->user()->telegrams()->where('telegram_id', $validate['telegram_id'])->first();

        if (!$telegram) {
            throw new \DomainException('Not found telegram account for provided id');
        }

        $telegram->delete();

        return response()->json([
            'success' => true,
            'message' => 'Successfully deleted telegram account'
        ]);
    }
}
