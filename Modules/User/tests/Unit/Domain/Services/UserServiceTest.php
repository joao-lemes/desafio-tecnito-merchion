<?php

declare(strict_types=1);

namespace Modules\User\Tests\Unit\Domain\Services;

use App\Exceptions\BadRequestException;
use App\Exceptions\NotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Mockery;
use Mockery\MockInterface;
use Modules\User\Domain\Models\User;
use Modules\User\Domain\Repositories\UserRepository;
use Modules\User\Domain\Services\UserService;
use Modules\User\Http\Resources\OutputUser;
use Modules\User\Http\Resources\OutputUserCollection;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    private UserService $userService;
    private MockInterface $userRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepository = Mockery::mock(UserRepository::class);
        $this->userService = new UserService($this->userRepository);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testListUsers(): void
    {
        $users = collect([
            new User(['uuid' => Str::uuid()->toString(), 'name' => 'Test User', 'email' => 'test@example.com'])
        ]);

        $paginator = new LengthAwarePaginator($users, $users->count(), 10, 1);

        $this->userRepository
            ->shouldReceive('list')
            ->with(1, 10)
            ->andReturn($paginator);

        $result = $this->userService->list(1, 10);

        $this->assertInstanceOf(OutputUserCollection::class, $result);
    }

    public function testStoreUser(): void
    {
        $userData = ['name' => 'Test User', 'email' => 'test@example.com', 'password' => 'password123'];
        $user = new User([
            'uuid' => Str::uuid()->toString(),
            'name' => $userData['name'],
            'email' => $userData['email'],
            'password' => bcrypt($userData['password']),
        ]);

        $this->userRepository
            ->shouldReceive('create')
            ->with(Mockery::on(function ($data) use ($userData) {
                return $data['name'] === $userData['name'] &&
                       $data['email'] === $userData['email'] &&
                       Str::isUuid($data['uuid']) &&
                       Hash::check($userData['password'], $data['password']);
            }))
            ->andReturn($user);

        $result = $this->userService->store($userData['name'], $userData['email'], $userData['password']);

        $this->assertInstanceOf(OutputUser::class, $result);
    }

    public function testGetUserByUuid(): void
    {
        $uuid = Str::uuid()->toString();
        $user = new User(['uuid' => $uuid, 'name' => 'Test User', 'email' => 'test@example.com']);

        $this->userRepository
            ->shouldReceive('getByUuid')
            ->with($uuid)
            ->andReturn($user);

        $result = $this->userService->getByUuid($uuid);

        $this->assertInstanceOf(OutputUser::class, $result);
    }

    public function testGetUserByUuidThrowsNotFoundException(): void
    {
        $uuid = Str::uuid()->toString();

        $this->userRepository
            ->shouldReceive('getByUuid')
            ->with($uuid)
            ->andReturn(null);

        $this->expectException(NotFoundException::class);

        $this->userService->getByUuid($uuid);
    }

    public function testUpdateUser(): void
    {
        $uuid = Str::uuid()->toString();
        $user = new User([
            'uuid' => $uuid,
            'name' => 'Old Name',
            'email' => 'old@example.com',
            'password' => bcrypt('oldpassword')
        ]);

        $this->userRepository
            ->shouldReceive('getByUuid')
            ->with($uuid)
            ->andReturn($user);

        $this->userRepository
            ->shouldReceive('update')
            ->once()
            ->with(Mockery::on(function ($updatedUser) {
                return $updatedUser->name === 'New Name' &&
                       $updatedUser->email === 'new@example.com' &&
                       Hash::check('newpassword', $updatedUser->password);
            }));

        $result = $this->userService->update(
            $uuid,
            'New Name',
            'new@example.com',
            'oldpassword',
            'newpassword'
        );

        $this->assertInstanceOf(OutputUser::class, $result);
    }

    public function testUpdateUserThrowsNotFoundException(): void
    {
        $uuid = Str::uuid()->toString();

        $this->userRepository
            ->shouldReceive('getByUuid')
            ->with($uuid)
            ->andReturn(null);

        $this->expectException(NotFoundException::class);

        $this->userService->update($uuid, 'New Name', 'new@example.com', 'oldpassword', 'newpassword');
    }

    public function testUpdateUserThrowsBadRequestExceptionOnWrongPassword(): void
    {
        $uuid = Str::uuid()->toString();
        $user = new User([
            'uuid' => $uuid,
            'name' => 'Old Name',
            'email' => 'old@example.com',
            'password' => bcrypt('oldpassword')
        ]);

        $this->userRepository
            ->shouldReceive('getByUuid')
            ->with($uuid)
            ->andReturn($user);

        $this->expectException(BadRequestException::class);

        $this->userService->update($uuid, 'New Name', 'new@example.com', 'wrongpassword', 'newpassword');
    }

    public function testDeleteUser(): void
    {
        $uuid = Str::uuid()->toString();
        $user = new User(['uuid' => $uuid, 'name' => 'Test User', 'email' => 'test@example.com']);

        $this->userRepository
            ->shouldReceive('getByUuid')
            ->with($uuid)
            ->andReturn($user);

        $this->userRepository
            ->shouldReceive('delete')
            ->with($user)
            ->once();

        Auth::shouldReceive('user')->andReturn(null);
        Auth::shouldReceive('logout')->never();

        $this->userService->delete($uuid);

        $this->assertTrue(true);
    }

    public function testDeleteUserThrowsNotFoundException(): void
    {
        $uuid = Str::uuid()->toString();

        $this->userRepository
            ->shouldReceive('getByUuid')
            ->with($uuid)
            ->andReturn(null);

        $this->expectException(NotFoundException::class);

        $this->userService->delete($uuid);
    }

    public function testDeleteUserLogsOutIfDeletingSelf(): void
    {
        $uuid = Str::uuid()->toString();
        $user = new User(['uuid' => $uuid, 'name' => 'Test User', 'email' => 'test@example.com']);

        $this->userRepository
            ->shouldReceive('getByUuid')
            ->with($uuid)
            ->andReturn($user);

        $this->userRepository
            ->shouldReceive('delete')
            ->with($user)
            ->once();

        Auth::shouldReceive('user')->andReturn($user);
        Auth::shouldReceive('logout')->once();

        $this->userService->delete($uuid);

        $this->assertTrue(true);
    }
}
