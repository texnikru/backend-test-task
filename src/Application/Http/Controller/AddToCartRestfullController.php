<?php

namespace Raketa\BackendTestTask\Application\Http\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\Application\Http\View\CartView;
use Raketa\BackendTestTask\Domain\CartManager;
use Raketa\BackendTestTask\Domain\Model\CartItem;
use Raketa\BackendTestTask\Domain\Repository\ProductRepositoryInterface;
use Ramsey\Uuid\Uuid;

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

        $product = $productUuid
            ? $this->productRepository->getByUuid($productUuid)
            : null;

        if (empty($product)) {
            return $this->error(reasonPhrase: "Product not found");
        }

        $cart = $this->cartManager->getCart();
        // Строку добавили, а кто сохранил?
        $cart->addItem(new CartItem(
            Uuid::uuid4(),
            $product->getUuid(),
            $product->getPrice(),
            $rawRequest['quantity'],
        ));

        return $this->json([
            'status' => 'success',
            'cart' => $this->cartView->toArray($cart)
        ]);

    }
}
