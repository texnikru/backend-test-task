<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Repository;

use Exception;
use Psr\Log\LoggerInterface;
use Raketa\BackendTestTask\Domain\Cart;
use Raketa\BackendTestTask\Infrastructure\Connector;
use Raketa\BackendTestTask\Infrastructure\ConnectorFactory;

readonly class CartManager
{
    public function __construct(
        private Connector       $connector,
        private LoggerInterface $logger,
    )
    {
    }

    /**
     * @inheritdoc
     */
    public function saveCart(Cart $cart)
    {
        try {
            $this->connector->set($cart, session_id()); // сессию принести в менеджере
        } catch (Exception $e) {
            $this->logger->error('Error'); // Ошибка ничего не поясняет, исключение не используется для разъяснения
        }
    }

    /**
     * @return ?Cart
     */
    public function getCart()
    {
        try {
            // не забота менеджера знать о сессиях
            return $this->connector->get(session_id()); // сессию принести в менеджере
        } catch (Exception $e) {
            $this->logger->error('Error'); // Ошибка ничего не поясняет
        }

        return new Cart(session_id(), []); // сессию принести в менеджере
    }
}
