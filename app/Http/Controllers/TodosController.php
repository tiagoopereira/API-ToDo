<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\TodosService;
use Illuminate\Http\JsonResponse;
use App\Helper\ResponseErrorHelper;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class TodosController extends Controller
{
    public function __construct(private TodosService $service) {}

    public function index(Request $request): JsonResponse
    {
        $user_id = Auth::user()->id;
        $per_page = $request->per_page;
        $entites = $this->service->findAll($per_page, $user_id);

        return response()->json($entites);
    }

    public function store(Request $request): JsonResponse
    {
        $this->validate($request, [
            'title' => 'required|string',
            'user_id' => 'required|uuid'
        ]);

        try {
            $data = $request->all();
            $entity = $this->service->create($data);

            return response()->json(['data' => $entity], JsonResponse::HTTP_CREATED);
        } catch (\Exception $e) {
            return ResponseErrorHelper::json($e->getMessage(), JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            $user_id = Auth::user()->id;
            $entity = $this->service->find($id, $user_id);

            return response()->json(['data' => $entity]);
        } catch (NotFoundResourceException $e) {
            return ResponseErrorHelper::json($e->getMessage(), JsonResponse::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return ResponseErrorHelper::json($e->getMessage(), JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(string $id, Request $request): JsonResponse
    {
        try {
            $user_id = Auth::user()->id;
            $data = $request->all();
            $entity = $this->service->update($id, $data, $user_id);

            return response()->json(['data' => $entity]);
        } catch (NotFoundResourceException $e) {
            return ResponseErrorHelper::json($e->getMessage(), JsonResponse::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return ResponseErrorHelper::json($e->getMessage(), JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        try {
            $user_id = Auth::user()->id;
            $this->service->delete($id, $user_id);

            return response()->json([], JsonResponse::HTTP_NO_CONTENT);
        } catch (NotFoundResourceException $e) {
            return ResponseErrorHelper::json($e->getMessage(), JsonResponse::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return ResponseErrorHelper::json($e->getMessage(), JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateStatus(string $id, string $status): JsonResponse
    {
        try {
            $user_id = Auth::user()->id;
            $entity = $this->service->updateStatus($id, $user_id, $status);

            return response()->json(['data' => $entity]);
        } catch (NotFoundResourceException $e) {
            return ResponseErrorHelper::json($e->getMessage(), JsonResponse::HTTP_NOT_FOUND);
        } catch (\InvalidArgumentException $e) {
            return ResponseErrorHelper::json($e->getMessage(), JsonResponse::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return ResponseErrorHelper::json($e->getMessage(), JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}