<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Repository;

use Exception;
use Psr\Log\LoggerInterface;
use Raketa\BackendTestTask\Domain\Cart;
use Raketa\BackendTestTask\Infrastructure\Connector;
use Raketa\BackendTestTask\Infrastructure\ConnectorFactory;

class CartManager
{
    public function __construct(
        private readonly Connector       $connector,
        private readonly LoggerInterface $logger,
    )
    {
    }

    /**
     * @inheritdoc
     */
    public function saveCart(Cart $cart)
    {
        try {
            $this->connector->set($cart, session_id());
        } catch (Exception $e) {
            $this->logger->error('Error');
        }
    }

    /**
     * @return ?Cart
     */
    public function getCart()
    {
        try {
            // не забота менеджера знать о сессиях
            return $this->connector->get(session_id());
        } catch (Exception $e) {
            $this->logger->error('Error');
        }

        return new Cart(session_id(), []);
    }
}
