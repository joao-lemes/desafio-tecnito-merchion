<?php

declare(strict_types=1);

namespace Modules\User\Tests\Unit\Domain\Services;

use App\Exceptions\UnauthorizedException;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Modules\User\Domain\Services\AuthService;
use Modules\User\Http\Resources\OutputLogin;
use Modules\User\Http\Resources\OutputLogout;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{
    private AuthService $authService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authService = new AuthService();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testLoginSuccessfully(): void
    {
        $credentials = ['email' => 'test@example.com', 'password' => 'password123'];
        $fakeToken = 'fake-jwt-token';
        $fakeTtl = 60;

        $authMock = Mockery::mock();
        $authMock->shouldReceive('attempt')->once()->with($credentials)->andReturn($fakeToken);
        $authMock->shouldReceive('factory')->once()->andReturnSelf();
        $authMock->shouldReceive('getTTL')->once()->andReturn($fakeTtl);

        $this->app->instance('auth', $authMock);

        $result = $this->authService->login($credentials);
        $output = json_decode(json_encode($result), true);

        $this->assertInstanceOf(OutputLogin::class, $result);
        $this->assertEquals($fakeToken, $output['data']['access_token']);
        $this->assertEquals($fakeTtl * 60, $output['data']['expires_in']);
    }

    public function testLoginThrowsUnauthorizedException(): void
    {
        $credentials = ['email' => 'invalid@example.com', 'password' => 'wrongpassword'];

        Auth::shouldReceive('attempt')
            ->once()
            ->with($credentials)
            ->andReturn(false);

        $this->expectException(UnauthorizedException::class);

        $this->authService->login($credentials);
    }

    public function testLogoutSuccessfully(): void
    {
        Auth::shouldReceive('logout')
            ->once();

        $result = $this->authService->logout();

        $data = $result->toArray(request());

        $this->assertInstanceOf(OutputLogout::class, $result);
        $this->assertTrue($data['data']['logout']);
    }
}
