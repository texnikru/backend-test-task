<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Domain;

use Exception;
use Psr\Log\LoggerInterface;
use Raketa\BackendTestTask\Domain\Model\Cart;
use Raketa\BackendTestTask\Domain\Storage\KeyValueStorageInterface;

readonly class CartManager
{
    public function __construct(
        private SessionManager           $sessionManager,
        private KeyValueStorageInterface $storage,
        private LoggerInterface          $logger,
    )
    {
    }

    public function saveCart(Cart $cart): void
    {
        try {
            $serializedCart = serialize($cart);
            $this->storage->set($serializedCart, $this->sessionManager->getSessionId());
        } catch (Exception $e) {
            $this->logger->error('Error ' . $e->getMessage());
        }
    }

    public function getCart(): ?Cart
    {
        try {
            $serializedCart = $this->storage->get($this->sessionManager->getSessionId());
            $cart = unserialize($serializedCart);

            return $cart;
        } catch (Exception $e) {
            $this->logger->error('Error ' . $e->getMessage());
        }

        return null;
    }
}
