<?php

declare(strict_types=1);

namespace Modules\User\Http\Resources;

use App\Http\Resource\OutputSuccess;

class OutputUser extends OutputSuccess
{
    /** @return array<string> */
    public function getData(): array
    {
        return [
            'user' => $this->resource->jsonSerialize(),
        ];
    }
}
