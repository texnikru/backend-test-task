<?php

namespace Raketa\BackendTestTask\Domain\Model;

class CustomerSession
{
    public function __construct(
        private string $sessionId,
    )
    {
    }

    public function getSessionId(): string
    {
        return $this->sessionId;
    }
}