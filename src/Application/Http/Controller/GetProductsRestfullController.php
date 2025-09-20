<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Application\Http\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\Application\Http\View\ProductsView;

readonly class GetProductsRestfullController extends AbstractRestfullController
{
    public function __construct(
        private ProductsView $productsVew
    )
    {
    }

    public function get(RequestInterface $request): ResponseInterface
    {
        $rawRequest = json_decode($request->getBody()->getContents(), true);
        $category = $rawRequest['category'] ?? null;

        if (empty($category)) {
            return $this->error('Category not found', 404);
        }

        return $this->json($this->productsVew->toArray($category));
    }
}
