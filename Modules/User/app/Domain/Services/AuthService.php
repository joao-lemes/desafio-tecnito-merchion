<?php

declare(strict_types=1);

namespace Modules\User\Domain\Services;

use App\Exceptions\UnauthorizedException;
use Modules\User\Http\Resources\OutputLogin;
use Modules\User\Http\Resources\OutputLogout;

class AuthService
{
    /** @param array<string> $credentials */
    public function login(array $credentials): OutputLogin
    {
        $token = auth()->attempt($credentials);

        if (! is_string($token)) {
            throw new UnauthorizedException();
        }

        return new OutputLogin($token);
    }

    public function logout(): OutputLogout
    {
        auth()->logout();

        return new OutputLogout(true);
    }
}
