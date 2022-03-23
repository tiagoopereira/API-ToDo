<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);

        $userData = $request->only('name', 'email', 'password');
        $userData['password'] = Hash::make($userData['password']);

        $user = User::create($userData);

        return response()->json($user, JsonResponse::HTTP_CREATED);
    }

    public function login(Request $request): JsonResponse
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $email = $request->email;
        $password = $request->password;

        $user = User::where('email', $email)->first();

        if (is_null($user) || !Hash::check($password, $user->password)) {
            return response()->json(
                [
                    'error' => true,
                    'message' => 'Wrong credentials.',
                    'code' => JsonResponse::HTTP_UNAUTHORIZED
                ],
                JsonResponse::HTTP_UNAUTHORIZED
            );
        }

        $token = $user->createToken('users');

        return response()->json([
            'access_token' => $token->accessToken,
            'expires_at' => $token->token->expires_at
        ]);
    }
}
