<?php

namespace App\Middleware;

use App\Services\JwtService;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class JwtMiddleware
{
    public function __construct(
        private readonly JwtService $jwtService,
        private readonly ResponseFactoryInterface $responseFactory
    ) {}

    public function __invoke(Request $request, RequestHandler $handler): ResponseInterface
    {
        // 1. Получаем заголовок Authorization
        $authHeader = $request->getHeaderLine('Authorization');

        if (empty($authHeader)) {
            return $this->unauthorized('Токен не предоставлен');
        }

        // 2. Извлекаем сам токен из строки 'Bearer <token>'
        $token = $this->jwtService->extractFromHeader($authHeader);
        if ($token === null) {
            return $this->unauthorized('Неверный формат. Ожидается: Bearer <token>');
        }

        try {
            // 3. Декодируем и верифицируем токен
            $payload = $this->jwtService->validateToken($token);

            // 4. Кладём payload в атрибуты запроса — доступно в Action
            $request = $request
                ->withAttribute('jwt_payload', $payload)
                ->withAttribute('user_id',   $payload->sub)
                ->withAttribute('user_role', $payload->role ?? 'user');

            // 5. Передаём управление следующему обработчику
            return $handler->handle($request);

        } catch (ExpiredException) {
            return $this->unauthorized('Токен истёк');
        } catch (SignatureInvalidException) {
            return $this->unauthorized('Неверная подпись токена');
        } catch (\Exception $e) {
            return $this->unauthorized('Недействительный токен: ' . $e->getMessage());
        }
    }

    private function unauthorized(string $message): ResponseInterface
    {
        $response = $this->responseFactory->createResponse(401);
        $response->getBody()->write(json_encode([
            'error'   => 'Unauthorized',
            'message' => $message,
        ], JSON_UNESCAPED_UNICODE));

        return $response->withHeader('Content-Type', 'application/json');
    }
}
