<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;


class ProfileController extends Controller
{

    public function indexWeb()
    {
        return view('common::profile');
    }

    public function calendarWeb()
    {
        return view('common::attendance-calendar');
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $status = $request->user()->update([
            'password' => Hash::make($request->password)
        ]);
        \DB::table('sessions')
            ->whereUserId(\auth()->id())
            ->delete();

        return ['success' => $status, 'message' => 'Password successfully changed'];
    }

    /**
     * Display the user's profile form.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function edit(Request $request)
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     *
     * @param \App\Http\Requests\ProfileUpdateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProfileUpdateRequest $request)
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
