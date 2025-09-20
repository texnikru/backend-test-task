<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Application\Http\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\Application\Http\View\CartView;
use Raketa\BackendTestTask\Domain\CartManager;
use Raketa\BackendTestTask\Domain\Model\CartItem;
use Raketa\BackendTestTask\Domain\Repository\ProductRepositoryInterface;

readonly class AddToCartRestfullController extends AbstractRestfullController
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private CartView                   $cartView,
        private CartManager                $cartManager,
    )
    {
    }

    public function get(RequestInterface $request): ResponseInterface
    {
        $rawRequest = json_decode($request->getBody()->getContents(), true);
        $productUuid = $rawRequest['productUuid'] ?? null;
        $quantity = (int)($rawRequest['quantity'] ?? null);

        if ($quantity < 1) {
            return $this->error("Quantity must be set and greater than 0");
        }

        $products = $productUuid
            ? $this->productRepository->getByUuids($productUuid)
            : null;

        if (empty($products)) {
            return $this->error("Product not found", 404);
        }

        $product  = array_shift($products);
        $newCart = $this->cartManager
            ->getCart()
            ->addItem(CartItem::ofProduct($product, $quantity));
        $this->cartManager->saveCart($newCart);

        return $this->json([
            'status' => 'success',
            'cart' => $this->cartView->toArray($newCart)
        ]);

    }
}
