<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Mail\ConfirmRegisteredUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class RegisterUserController extends Controller
{
    public function store(RegisterUserRequest $request): JsonResponse
    {
        $userInfo = $request->validated();

        $user = new User($userInfo);
        $user->password = Hash::make($userInfo['password']);

        /** Skip this
         * 
         $signedUrl = URL::signedRoute('register-user.verify', [
             'user' => $user
            ]);
            
        Mail::to($user->email)->send(new ConfirmRegisteredUser($signedUrl));
         */

        return response()->json([
            'message' => 'User created.'
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
