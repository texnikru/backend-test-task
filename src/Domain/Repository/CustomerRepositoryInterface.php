<?php

namespace Raketa\BackendTestTask\Domain\Repository;

use Raketa\BackendTestTask\Domain\Model\Customer;
use Raketa\BackendTestTask\Domain\Model\CustomerSession;

interface CustomerRepositoryInterface
{
    public function getBySession(CustomerSession $session): Customer;
}