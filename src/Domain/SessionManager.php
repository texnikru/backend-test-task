<?php

namespace Raketa\BackendTestTask\Domain;

use Raketa\BackendTestTask\Domain\Model\CustomerSession;

class SessionManager
{
    public function getSession(): CustomerSession
    {
        return new CustomerSession(session_id());
    }
}