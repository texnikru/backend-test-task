<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Application\Http\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\Application\Http\View\ProductsView;
use Raketa\BackendTestTask\Domain\Repository\CategoryRepositoryInterface;

readonly class GetProductsRestfullController extends AbstractRestfullController
{
    public function __construct(
        private ProductsView                $productsVew,
        private CategoryRepositoryInterface $categoryRepository,
    )
    {
    }

    public function get(RequestInterface $request): ResponseInterface
    {
        $rawRequest = json_decode($request->getBody()->getContents(), true);
        $categoryName = $rawRequest['category'] ?? null;

        $category = $categoryName
            ? $this->categoryRepository->getByName($categoryName)
            : null;

        if (empty($category)) {
            return $this->error('Category not found', 404);
        }

        return $this->json($this->productsVew->toArray($category));
    }
}
