<?php

declare(strict_types=1);

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\User\Domain\Services\UserService;
use Modules\User\Http\Requests\DeleteUserRequest;
use Modules\User\Http\Requests\GetUserRequest;
use Modules\User\Http\Requests\ListUserRequest;
use Modules\User\Http\Requests\StoreUserRequest;
use Modules\User\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    public function __construct(private readonly UserService $userService)
    {
    }

    public function listAction(ListUserRequest $request): JsonResponse
    {
        $output = $this->userService->list(
            (int) $request->get('page') ?? 1,
            (int) $request->get('per_page') ?? 10
        );

        return response()->json($output, JsonResponse::HTTP_OK);
    }

    public function storeAction(StoreUserRequest $request): JsonResponse
    {
        $output = $this->userService->store(
            $request->get('name'),
            $request->get('email'),
            $request->get('password')
        );

        return response()->json($output, JsonResponse::HTTP_CREATED);
    }

    public function getAction(GetUserRequest $request): JsonResponse
    {
        $output = $this->userService->getByUuid($request->id);

        return response()->json($output, JsonResponse::HTTP_OK);
    }

    public function updateAction(UpdateUserRequest $request): JsonResponse
    {
        $output = $this->userService->update(
            $request->id,
            $request->get('name') ?? null,
            $request->get('email') ?? null,
            $request->get('current_password') ?? null,
            $request->get('password') ?? null,
        );

        return response()->json($output, JsonResponse::HTTP_OK);
    }

    public function deleteAction(DeleteUserRequest $request): JsonResponse
    {
        $this->userService->delete(
            $request->id,
        );

        return response()->json([], JsonResponse::HTTP_NO_CONTENT);
    }
}
