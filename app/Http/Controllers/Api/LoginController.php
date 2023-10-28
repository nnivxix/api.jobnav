<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\LoginResource;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginController extends Controller
{
    public function store(LoginRequest $request)
    {
        if ($request->authenticated()) {
            $user = auth()->user();
            $token = $user->createToken('authToken')->plainTextToken;

            return LoginResource::make($user)
                ->additional([
                    'token'   => $token,
                    'message' => 'Login successful.'
                ]);
        }

        return response()->json(['errors' => "Email or password is wrong."], 401);
    }
}
