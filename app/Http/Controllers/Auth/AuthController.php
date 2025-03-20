<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\EgovCodeRequest;
use App\Services\AuthService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Modules\User\Entities\User;

class AuthController extends Controller
{

    public function __construct(private AuthService $authService)
    {
    }

    public function authenticate()
    {

        return redirect($this->authService->getLoginRedirectQueryURL());
    }

    public function callback(EgovCodeRequest $request)
    {
        $code = $request->get('code');

        try {
            $credentials = $this->authService->getAccessCredentials($code);

            $userData = $this->authService->getUserData($credentials->access_token);

            $user = User::where('pinfl', '=', $userData?->pin)->first();

            DB::table('oauth_logs')
                ->insert([
                    'code' => $code,
                    'sess_id' => $userData?->sess_id,
                    'user_id' => $user?->id,
                    'payload' => json_encode($userData),
                    'created_at' => now(),
                    'updated_at' => now(),
                    'first_name' => $userData?->first_name,
                    'sur_name' => $userData?->sur_name,
                    'mid_name' => $userData?->mid_name,
                    'birth_date' => $userData?->birth_date,
                    'egov_user_id' => $userData?->user_id,
                    'email' => $userData?->email,
                    'pport_no' => $userData?->pport_no,
                    'pin' => $userData?->pin,
                ]);

            if (!$user) {
                return redirect()->route('login')->withErrors(['oauth' => trans('auth.failed')]);
            }

            \Auth::login($user);

            $user->last_sess_id = $userData->sess_id;
            $user->save();

            return redirect(AuthRedirectHelper::handle($user));
        } catch (\Throwable $exception) {
            return redirect()->route('login')->withErrors(['oauth' => 'Bad Request!']);
        }

    }
}
