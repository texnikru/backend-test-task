<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Application\Http\Controller;

use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\Application\Http\View\CartView;
use Raketa\BackendTestTask\Domain\CartManager;

readonly class GetCartRestfullController extends AbstractRestfullController
{
    public function __construct(
        private CartView    $cartView,
        private CartManager $cartManager
    )
    {
    }

    public function get(): ResponseInterface
    {
        $cart = $this->cartManager->getCart();

        if (!$cart) {
            return $this->error(404, 'Cart not found');
        }

        return $this->json($this->cartView->toArray($cart));
    }
}
