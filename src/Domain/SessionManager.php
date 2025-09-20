<?php

namespace Raketa\BackendTestTask\Domain;

class SessionManager
{
    public function getSessionId(): string
    {
        return session_id();
    }
}