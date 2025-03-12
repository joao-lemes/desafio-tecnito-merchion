<?php

declare(strict_types=1);

namespace Modules\User\Tests\Unit\Domain\Models;

use Illuminate\Foundation\Testing\WithFaker;
use Modules\User\Domain\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    use WithFaker;

    public function testItHasFillableAttributes(): void
    {
        $user = new User();

        $this->assertEquals(['uuid', 'name', 'email', 'password'], $user->getFillable());
    }

    public function testItGeneratesJwtIdentifier(): void
    {
        $user = new User();
        $user->id = 1;

        $this->assertEquals(1, $user->getJWTIdentifier());
    }

    public function testItReturnsEmptyArrayForJwtCustomClaims(): void
    {
        $user = new User();
        $this->assertEquals([], $user->getJWTCustomClaims());
    }

    public function testItSerializesToJsonCorrectly(): void
    {
        $user = new User();
        $user->uuid = $this->faker->uuid();
        $user->name = $this->faker->name();
        $user->email = $this->faker->email();
        $user->created_at = now();
        $user->updated_at = now();

        $expectedJson = [
            'id' => $user->uuid,
            'name' => $user->name,
            'email' => $user->email,
            'created_at' => $user->created_at->toIso8601String(),
            'updated_at' => $user->updated_at->toIso8601String(),
        ];

        $this->assertEquals($expectedJson, $user->jsonSerialize());
    }
}
