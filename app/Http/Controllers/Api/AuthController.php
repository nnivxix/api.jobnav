<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\AuthenticatedUserResource;

class AuthController extends Controller
{
    public function index()
    {
        $user = auth()
            ->user()
            ->load('experiences', 'jobs');

        return AuthenticatedUserResource::make($user);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name'     => 'required',
            'email'    => 'email',
            'username' => 'required|unique:users,username,' . auth()->id(),
            'header'   => 'nullable|string',
            'avatar'   => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'cover'    => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'skills'   => 'nullable|string',
        ]);

        $user = auth()->user();

        if ($request->password) {
            // change password
        }

        $profileInfo = [
            'bio'    => $request->bio,
            'skills' => $request->skills,
        ];
        $userInfo = [
            'name'     => $request->name,
            'username' => $request->username,
            'email'    => $request->email
        ];

        $avatar = $request->file('avatar');

        if ($avatar) {
            $avatar_file_name = Str::random(40) . '.' . $avatar->getClientOriginalExtension();

            if ($user->profile->avatar && Storage::disk('public')->exists($user->profile->avatar)) {
                Storage::delete('public/' . $user->profile->avatar);
            }

            $avatar->storePubliclyAs('users/avatars', $avatar_file_name, 'public');
            $profileInfo['avatar'] = 'users/avatars/' . $avatar_file_name;
        }

        $cover = $request->file('cover');

        if ($cover) {
            $cover_file_name = Str::random(40) . '.' . $cover->getClientOriginalExtension();

            if ($user->profile->cover && Storage::disk('public')->exists($user->profile->cover)) {
                Storage::delete('public/' . $user->profile->cover);
            }

            $cover->storePubliclyAs('users/covers', $cover_file_name, 'public');
            $profileInfo['cover'] = 'users/covers/' . $cover_file_name;
        }


        $user->update($userInfo);
        $user->profile->update($profileInfo);

        return AuthenticatedUserResource::make($user)
            ->additional([
                'message' => 'Profile updated successfully.'
            ]);
    }
    public function destroy(Request $request)
    {
        auth()->user()
            ->currentAccessToken()
            ->delete();

        return [
            'message' => 'Logged out'
        ];
    }
}
