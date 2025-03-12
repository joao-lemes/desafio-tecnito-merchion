<?php

declare(strict_types=1);

namespace Modules\User\Http\Resources;

use App\Http\Resource\OutputSuccess;

class OutputUserCollection extends OutputSuccess
{
    /** @return array<string> */
    public function getData(): array
    {
        return [
            'users' => $this->resource->transform(function ($user) {
                return $user->jsonSerialize();
            }),
            'meta' => [
                'current_page' => $this->currentPage(),
                'per_page' => $this->perPage(),
                'total' => $this->total(),
                'last_page' => $this->lastPage(),
            ],
        ];
    }
}
