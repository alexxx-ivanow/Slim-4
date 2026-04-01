<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\BeforeValidException;
use stdClass;

class JwtService
{
    public function __construct(
        private readonly string $secret,
        private readonly string $algorithm = 'HS256',
        private readonly int    $expire = 3600
    ) {}

    /**
     * Создаём access-токен
     */
    public function generateToken(array $payload): string
    {
        $now = time();

        $claims = array_merge($payload, [
            'iat' => $now,               // issued at (время выпуска)
            'nbf' => $now,               // not before (действителен с)
            'exp' => $now + $this->expire, // expiration (время истечения)
        ]);

        return JWT::encode($claims, $this->secret, $this->algorithm);
    }

    /**
     * Генерируем refresh-токен
     */
    public function generateRefreshToken(int $userId): string
    {
        $now = time();

        $claims = [
            'sub'  => $userId,
            'type' => 'refresh',
            'iat'  => $now,
            'exp'  => $now + $this->expire,
        ];

        return JWT::encode($claims, $this->secret, $this->algorithm);
    }

    /**
     * Декодируем и верифицируем токен
     * Возвращает payload или бросает исключение
     */
    public function validateToken(string $token): stdClass
    {
        return JWT::decode(
            $token,
            new Key($this->secret, $this->algorithm)
        );
    }

    /**
     * Извлекаем токен из заголовка Authorization
     * Формат: 'Bearer <token>'
     */
    public function extractFromHeader(string $header): ?string
    {
        if (preg_match('/^Bearer\s+(\S+)$/', $header, $m)) {
            return $m[1];
        }
        return null;
    }
}
