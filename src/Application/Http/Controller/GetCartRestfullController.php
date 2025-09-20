<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Application\Http\Controller;

use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\Application\Http\View\CartView;
use Raketa\BackendTestTask\Domain\CartManager;
use Raketa\BackendTestTask\Domain\SessionManager;

readonly class GetCartRestfullController extends AbstractRestfullController
{
    public function __construct(
        private CartView    $cartView,
        private CartManager $cartManager,
        private SessionManager $sessionManager,
    )
    {
    }

    public function get(): ResponseInterface
    {
        $cart = $this->cartManager->getCart($this->sessionManager->getSession());

        if (!$cart) {
            return $this->error('Cart not found', 404);
        }

        return $this->json($this->cartView->toArray($cart));
    }
}
