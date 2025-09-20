<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Infrastructure\Http\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\Domain\CartManager;
use Raketa\BackendTestTask\Infrastructure\Http\View\CartView;

readonly class GetCartRestfullController extends AbstractRestfullController
{
    public function __construct(
        private CartView    $cartView,
        private CartManager $cartManager
    )
    {
    }

    public function get(RequestInterface $request): ResponseInterface
    {
        $cart = $this->cartManager->getCart();

        if (!$cart) {
            return $this->error(404, 'Cart not found');
        }

        return $this->json($this->cartView->toArray($cart));
    }
}
