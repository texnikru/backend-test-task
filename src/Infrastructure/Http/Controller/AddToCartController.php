<?php

namespace Raketa\BackendTestTask\Infrastructure\Http\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\Domain\CartManager;
use Raketa\BackendTestTask\Domain\Model\CartItem;
use Raketa\BackendTestTask\Infrastructure\Http\JsonResponse;
use Raketa\BackendTestTask\Infrastructure\Http\View\CartView;
use Raketa\BackendTestTask\Infrastructure\Repository\ProductRepository;
use Ramsey\Uuid\Uuid;

readonly class AddToCartController
{
    public function __construct(
        private ProductRepository $productRepository,
        private CartView $cartView,
        private CartManager $cartManager,
    ) {
    }

    public function get(RequestInterface $request): ResponseInterface
    {
        $rawRequest = json_decode($request->getBody()->getContents(), true);
        $product = $this->productRepository->getByUuid($rawRequest['productUuid']);

        if (empty($product)) {
            throw new \Exception('404 or something');
        }

        $cart = $this->cartManager->getCart();
        // Строку добавили, а кто сохранил?
        $cart->addItem(new CartItem(
            Uuid::uuid4(),
            $product->getUuid(),
            $product->getPrice(),
            $rawRequest['quantity'],
        ));

        // Лучше быть JsonResponse::withBody
        $response = new JsonResponse();
        $response->getBody()->write(
            json_encode(
                [
                    'status' => 'success',
                    'cart' => $this->cartView->toArray($cart)
                ],
                JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
            )
        );

        return $response
            ->withHeader('Content-Type', 'application/json; charset=utf-8')
            ->withStatus(200);
    }
}
