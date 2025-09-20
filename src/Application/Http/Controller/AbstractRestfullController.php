<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Application\Http\Controller;

use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\Application\Http\JsonResponse;

readonly abstract class AbstractRestfullController
{
    protected function error(int $httpStatus = 400, string $reasonPhrase = ''): ResponseInterface
    {
        return (new JsonResponse())
            ->withStatus($httpStatus, $reasonPhrase);
    }

    protected function json(array $data): ResponseInterface
    {
        $response = new JsonResponse();
        $response->getBody()->write(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        return $response
            ->withStatus(200);
    }
}
