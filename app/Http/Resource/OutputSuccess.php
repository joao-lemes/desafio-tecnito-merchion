<?php

declare(strict_types=1);

namespace App\Http\Resource;

use Illuminate\Http\Resources\Json\JsonResource;

abstract class OutputSuccess extends JsonResource
{
    /** @return array<mixed> */
    public function toArray($request): array
    {
        return [
            'success' => true,
            'data' => $this->getData(),
        ];
    }

    abstract protected function getData();
}
