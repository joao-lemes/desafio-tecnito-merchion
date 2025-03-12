<?php

declare(strict_types=1);

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\User\Domain\Services\AuthService;
use Modules\User\Http\Requests\LoginRequest;

class AuthController extends Controller
{
    public function __construct(private readonly AuthService $authService)
    {
    }

    public function loginAction(LoginRequest $request): JsonResponse
    {
        $output = $this->authService->login($request->all());

        return response()->json($output, JsonResponse::HTTP_OK);
    }

    public function logoutAction(): JsonResponse
    {
        $output = $this->authService->logout();

        return response()->json($output, JsonResponse::HTTP_OK);
    }
}
