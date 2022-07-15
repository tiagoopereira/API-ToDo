<?php

namespace App\Http\Controllers;

use App\Helper\ResponseErrorHelper;
use App\Service\AuthService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService)
    {
    }

    public function login(Request $request): JsonResponse
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
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
