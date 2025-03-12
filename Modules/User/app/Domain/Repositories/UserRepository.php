<?php

declare(strict_types=1);

namespace Modules\User\Domain\Repositories;

use Modules\User\Domain\Models\User;

interface UserRepository
{
    /** @param array<string> $attributes */
    public function create(array $attributes): User;
}
