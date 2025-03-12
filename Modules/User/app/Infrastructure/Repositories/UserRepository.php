<?php

declare(strict_types=1);

namespace Modules\User\Infrastructure\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\User\Domain\Models\User;
use Modules\User\Domain\Repositories\UserRepository as IUserRepository;

class UserRepository implements IUserRepository
{
    public function __construct(private readonly User $model)
    {
    }

    public function list(int $page, int $perPage): LengthAwarePaginator
    {
        return $this->model->paginate(
            $perPage,
            ['uuid', 'name', 'email', 'created_at', 'updated_at'],
            'page',
            $page
        );
    }

    /** @param array<string> $attributes */
    public function create(array $attributes): User
    {
        return $this->model->create($attributes);
    }

    public function getByUuid(string $uuid): ?User
    {
        return $this->model->where('uuid', $uuid)->first();
    }
}
