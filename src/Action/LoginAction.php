<?php

namespace App\Action;


use App\Services\JwtService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Container\ContainerInterface;

use Illuminate\Database\Capsule\Manager;
use App\Models\User;

final class LoginAction
{

    //private $container;
    public $JwtService;
    public function __construct(
        JwtService $jwtService,
        Manager $connection,
        //ContainerInterface $container
    ) {
        //$this->container = $container;
        $this->JwtService = $jwtService;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface      $response
    ): ResponseInterface {
        $body     = (array) $request->getParsedBody();
        $email    = trim($body['email']    ?? '');
        $password = trim($body['password'] ?? '');

        if (empty($email) || empty($password)) {
            return $this->json($response, ['error' => 'Email и пароль обязательны', 'body' => $body], 422);
        }

        //$settings = $this->container->get('settings');

        // Ищем пользователя через Eloquent (модель User)
        $user = User::where('email', $email)->first();

        //if (!$user || !password_verify($password, $user->password)) {
        if (!$user || $password !== $user->password) {
            return $this->json($response, ['error' => 'Неверный email или пароль'], 401);
        }

        // Формируем payload токена
        $payload = [
            'sub'   => $user->id,
            'email' => $user->email,
            'role'  => $user->role ?? 'user',
        ];

        $accessToken  = $this->JwtService->generateToken($payload);
        $refreshToken = $this->JwtService->generateRefreshToken($user->id);

        return $this->json($response, [
            'access_token'  => $accessToken,
            'refresh_token' => $refreshToken,
            'token_type'    => 'Bearer',
            'expires_in'    => 3600,
        ]);
    }

    private function json(ResponseInterface $r, array $data, int $status = 200): ResponseInterface
    {
        $r->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE));
        return $r->withStatus($status)->withHeader('Content-Type', 'application/json');
    }
}
