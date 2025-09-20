<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Domain;

use Exception;
use Psr\Log\LoggerInterface;
use Raketa\BackendTestTask\Domain\Model\Cart;
use Raketa\BackendTestTask\Domain\Storage\StorageInterface;

readonly class CartManager
{
    public function __construct(
        private StorageInterface $storage,
        private LoggerInterface  $logger,
    )
    {
    }

    public function saveCart(Cart $cart): void
    {
        try {
            $serializedCart = serialize($cart);
            $this->storage->set($serializedCart, $this->getSession());
        } catch (Exception $e) {
            $this->logger->error('Error ' . $e->getMessage());
        }
    }

    public function getCart(): ?Cart
    {
        try {
            $serializedCart = $this->storage->get($this->getSession());
            $cart = unserialize($serializedCart);

            return $cart;
        } catch (Exception $e) {
            $this->logger->error('Error ' . $e->getMessage());
        }

        return null;
    }

    // сессию принести в менеджере, пока проблему вынес
    private function getSession(): string
    {
        return session_id();
    }
}
