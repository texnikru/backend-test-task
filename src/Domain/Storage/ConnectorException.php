<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Domain\Storage;

use Exception;

class ConnectorException extends Exception
{
    public function __toString(): string
    {
        return sprintf(
            '[%s] %s in %s on line %s',
            $this->getCode(),
            $this->getMessage(),
            $this->getFile(),
            $this->getLine(),
        );
    }
}
