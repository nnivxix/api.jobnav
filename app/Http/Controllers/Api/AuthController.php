<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\AuthenticatedUserResource;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        $user = auth()
            ->user()
            ->load('experiences');

        return AuthenticatedUserResource::make($user);
    }
    public function store(Request $request)
    {
        $credential = $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        $authenticahed = Auth::attempt($credential);

        if (!$authenticahed) {
            return response()->json([
                'data' => [
                    'user'  => null,
                    'token' => null,
                ],
                'messsage' => 'failed'
            ]);
        }

        $user = $request->user();
        $token = $request->user()
            ->createToken('user-token', ['*'], now()->addHours(5))
            ->plainTextToken;

        return response()->json([
            'data' => [
                'user'  => $user,
                'token' => $token
            ],
            'message' => 'success'
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
