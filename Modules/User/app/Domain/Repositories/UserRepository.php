<?php

declare(strict_types=1);

namespace Modules\User\Domain\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\User\Domain\Models\User;

interface UserRepository
{
    public function list(int $page, int $perPage): LengthAwarePaginator;

    /** @param array<string> $attributes */
    public function create(array $attributes): User;
}
