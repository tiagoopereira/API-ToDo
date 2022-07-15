<?php

namespace App\Http\Controllers;

use App\Helper\ResponseErrorHelper;
use App\Service\UsersService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    public function __construct(private UsersService $service)
    {
    }

    public function store(Request $request): JsonResponse
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        try {
            $data = $request->only('name', 'email', 'password');
            $user = $this->service->create($data);

            return response()->json(['data' => $user], JsonResponse::HTTP_CREATED);
        } catch (\Exception $e) {
            return ResponseErrorHelper::json($e->getMessage(), JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(): JsonResponse
    {
        try {
            $user = Auth::user();

            return response()->json(['data' => $user]);
        } catch (\Exception $e) {
            return ResponseErrorHelper::json($e->getMessage(), JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request): JsonResponse
    {
        $id = Auth::user()->id;

        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'required',
        ]);

        try {
            $data = $request->only('name', 'email', 'password');
            $user = $this->service->update($id, $data);

            return response()->json(['data' => $user]);
        } catch (\Exception $e) {
            return ResponseErrorHelper::json($e->getMessage(), JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(): JsonResponse
    {
        try {
            $id = Auth::user()->id;
            $this->service->delete($id);

            return response()->json([], JsonResponse::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return ResponseErrorHelper::json($e->getMessage(), JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
