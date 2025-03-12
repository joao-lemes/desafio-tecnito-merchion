<?php

declare(strict_types=1);

namespace Modules\User\Http\Middleware;

use App\Exceptions\UnauthorizedException;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtMiddleware
{
    /** @return RedirectResponse|Response */
    public function handle(Request $request, Closure $next)
    {
        try {
            JWTAuth::parseToken()->authenticate();
        } catch (Exception) {
            throw new UnauthorizedException();
        }

        return $next($request);
    }
}
