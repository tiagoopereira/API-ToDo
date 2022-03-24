<?php

namespace App\Http\Controllers;

use App\Service\AuthService;
use App\Helper\ResponseErrorHelper;
use App\Service\UsersService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService,
        private UsersService $UsersService
    )
    {
    }

    public function register(Request $request): JsonResponse
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);

        try {
            $data = $request->only('name', 'email', 'password');
            $user = $this->UsersService->create($data);

            return response()->json($user, JsonResponse::HTTP_CREATED);
        } catch (\Exception $e) {
            return ResponseErrorHelper::json($e->getMessage(), JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function login(Request $request): JsonResponse
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        try {
            $email = $request->email;
            $password = $request->password;
            $response = $this->authService->login($email, $password);

            return response()->json($response);
        } catch (AuthenticationException $e) {
            return ResponseErrorHelper::json($e->getMessage(), JsonResponse::HTTP_UNAUTHORIZED);
        } catch (\Exception $e) {
            return ResponseErrorHelper::json($e->getMessage(), JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
