<?php

declare(strict_types=1);

namespace Modules\User\Http\Resources;

use App\Http\Resource\OutputSuccess;

class OutputLogout extends OutputSuccess
{
    /** @return array<bool> */
    protected function getData(): array
    {
        return [
            'logout' => $this->resource,
        ];
    }
}
