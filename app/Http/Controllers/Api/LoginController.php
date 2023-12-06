<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\LoginResource;

class LoginController extends Controller
{
    public function store(LoginRequest $request)
    {
        if ($request->authenticated()) {
            /** @var \App\Models\User $user **/
            $user = auth()->user();
            $token = $user->createToken('authToken')->plainTextToken;

            return response()->json([
                'user' => LoginResource::make($user),
                'token'   => $token,
            ]);
        }

        return response()->json([
            'errors' => [
                "message" => "Email or password is wrong."
            ]
        ], 401);
    }
}
