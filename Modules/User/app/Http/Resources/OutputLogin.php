<?php

declare(strict_types=1);

namespace Modules\User\Http\Resources;

use App\Http\Resource\OutputSuccess;

class OutputLogin extends OutputSuccess
{
    /** @return array<mixed> */
    protected function getData(): array
    {
        return [
            'access_token' => $this->resource,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
        ];
    }
}
