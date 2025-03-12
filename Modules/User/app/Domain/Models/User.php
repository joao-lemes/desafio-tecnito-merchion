<?php

declare(strict_types=1);

namespace Modules\User\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use JsonSerializable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, JsonSerializable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    /** @var array<string> */
    protected $fillable = [
        'uuid',
        'name',
        'email',
        'password',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /** @return array<mixed> */
    public function getJWTCustomClaims(): array
    {
        return [];
    }

    /** @return array<string> */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->uuid,
            'name' => $this->name,
            'email' => $this->email,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}
