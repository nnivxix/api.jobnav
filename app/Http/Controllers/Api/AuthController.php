<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AuthenticatedUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\PersonalAccessToken;
use App\Http\Resources\AuthenticatedUserResource;

class AuthController extends Controller
{
    public function index()
    {
        /** 
         * @var \App\Models\User $user
         */
        $user = auth()->user();
        $user->loadMissing('experiences', 'jobs');

        return AuthenticatedUserResource::make($user);
    }

    public function update(AuthenticatedUserRequest $request)
    {
        $request->validated();

        $user = User::firstWhere('id', auth()->id());

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
        // Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        /** 
         * @var \App\Models\User $user
         */
        $user = auth('sanctum')->user();
        $token = $user->currentAccessToken();

        if ($token instanceof PersonalAccessToken) {
            $token->delete();
        }


        return response()->json([
            'message' => 'Logged out.'
        ]);
    }
}
