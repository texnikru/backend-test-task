<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Domain;

use Exception;
use Psr\Log\LoggerInterface;
use Raketa\BackendTestTask\Domain\Model\Cart;
use Raketa\BackendTestTask\Domain\Model\CustomerSession;
use Raketa\BackendTestTask\Domain\Repository\CustomerRepositoryInterface;
use Raketa\BackendTestTask\Domain\Storage\KeyValueStorageInterface;
use Ramsey\Uuid\Uuid;

readonly class CartManager
{
    private const CART_TTL_SECONDS = 24 * 60 * 60;

    public function __construct(
        private CustomerRepositoryInterface $customerRepository,
        private KeyValueStorageInterface    $storage,
        private LoggerInterface             $logger,
    )
    {
    }

    public function saveCart(Cart $cart, CustomerSession $session): void
    {
        try {
            $serializedCart = serialize($cart);
            $this->storage->set($serializedCart, $session->getSessionId(), self::CART_TTL_SECONDS);
        } catch (Exception $e) {
            $this->logger->error('Error ' . $e->getMessage());
        }
    }

    public function getCart(CustomerSession $session): ?Cart
    {
        try {
            $serializedCart = $this->storage->get($session->getSessionId());
            $cart = unserialize($serializedCart);

            return $cart;
        } catch (Exception $e) {
            $this->logger->error('Error ' . $e->getMessage());
        }

        return null;
    }

    public function emptyCart(CustomerSession $session): Cart
    {
        return new Cart(
            Uuid::uuid4(),
            $this->customerRepository->getBySession($session),
            'defaultPaymentMethod',
            [],
        );
    }
}
