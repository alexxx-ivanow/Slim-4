<?php

namespace App\Action;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class ProfileAction
{
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface      $response
    ): ResponseInterface {
        // JwtMiddleware уже верифицировал токен и положил данные в атрибуты
        $userId  = $request->getAttribute('user_id');
        $payload = $request->getAttribute('jwt_payload');

        $data = [
            'id'    => $userId,
            'email' => $payload->email,
            'role'  => $payload->role ?? 'user',
        ];

        $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
