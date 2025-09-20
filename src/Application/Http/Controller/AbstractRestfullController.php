<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Application\Http\Controller;

use Nyholm\Psr7\Stream;
use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\Application\Http\JsonResponse;

readonly abstract class AbstractRestfullController
{
    protected function error(string $reasonPhrase, int $httpStatus = 400): ResponseInterface
    {
        return (new JsonResponse())
            ->withStatus($httpStatus, $reasonPhrase);
    }

    protected function json(array $data): ResponseInterface
    {
        return (new JsonResponse())
            ->withStatus(200)
            ->withBody(Stream::create(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)));
    }
}
