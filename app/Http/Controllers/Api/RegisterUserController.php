<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

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
            'header' => null,
            'avatar' => null,
            'cover'  => null,
            'skills' => null,
        ]);

        return response()->json([
            'user'    => $user,
            'token'   => $user->createToken('user-token')->plainTextToken,
            'message' => 'user created'
        ], 201);
    }
}
