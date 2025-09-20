<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Infrastructure\Http\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\Infrastructure\Http\JsonResponse;
use Raketa\BackendTestTask\Infrastructure\Http\View\ProductsView;

readonly class GetProductsController
{
    public function __construct(
        private ProductsView $productsVew
    ) {
    }

    public function get(RequestInterface $request): ResponseInterface
    {
        $response = new JsonResponse();

        $rawRequest = json_decode($request->getBody()->getContents(), true); // JSON_THROW_ON_ERROR

        $response->getBody()->write(
            json_encode(
                $this->productsVew->toArray($rawRequest['category']),
                JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
            )
        );

        return $response
            ->withHeader('Content-Type', 'application/json; charset=utf-8')
            ->withStatus(200);
    }
}
