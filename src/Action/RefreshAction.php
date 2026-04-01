<?php

namespace App\Action;

use App\Models\User;
use App\Services\JwtService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class RefreshAction
{
    public function __construct(
        private readonly JwtService $jwtService
    ) {}

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface      $response
    ): ResponseInterface {
        $body         = (array) $request->getParsedBody();
        $refreshToken = trim($body['refresh_token'] ?? '');

        if (empty($refreshToken)) {
            return $this->json($response, ['error' => 'refresh_token обязателен'], 422);
        }

        try {
            $payload = $this->jwtService->validateToken($refreshToken);

            // Проверяем тип токена
            if (($payload->type ?? '') !== 'refresh') {
                return $this->json($response, ['error' => 'Это не refresh-токен'], 401);
            }

            $user = User::findOrFail((int) $payload->sub);

            $newAccessToken = $this->jwtService->generateToken([
                'sub'   => $user->id,
                'email' => $user->email,
                'role'  => $user->role ?? 'user',
            ]);

            return $this->json($response, [
                'access_token' => $newAccessToken,
                'token_type'   => 'Bearer',
                'expires_in'   => 3600,
            ]);

        } catch (\Exception $e) {
            return $this->json($response, ['error' => $e->getMessage()], 401);
        }
    }

    private function json(ResponseInterface $r, array $data, int $status = 200): ResponseInterface
    {
        $r->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE));
        return $r->withStatus($status)->withHeader('Content-Type', 'application/json');
    }
}
