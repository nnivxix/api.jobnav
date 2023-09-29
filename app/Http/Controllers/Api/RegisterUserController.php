<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\ConfirmRegisteredUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class RegisterUserController extends Controller
{
    public function store(Request $request)
    {
        $userInfo = $request->validate([
            'name'     => 'required',
            'username' => 'required|unique:users',
            'email'    => 'required|email|unique:users',
            'password' => 'required',
        ]);

        $userInfo['password'] = Hash::make($userInfo['password']);
        $user = User::create($userInfo);

        $user->profile()->create([
            'bio'    => null,
            'avatar' => null,
            'cover'  => null,
            'skills' => null,
        ]);

        $signedUrl = URL::signedRoute('register-user.verify', [
            'user' => $user
        ]);

        Mail::to($user->email)->send(new ConfirmRegisteredUser($signedUrl));

        return response()->json([
            'user'    => $user,
            // 'token'   => $user->createToken('user-token')->plainTextToken,
            'message' => 'user created'
        ], 201);
    }

    public function verify(Request $request, User $user)
    {
        abort_if(!$request->hasValidSignature(), 403);

        $user->update([
            'email_verified_at' => now()
        ]);

        return response()->json([
            'message' => 'should be redirect'
        ]);
    }
}
