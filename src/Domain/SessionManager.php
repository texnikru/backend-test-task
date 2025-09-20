<?php

namespace Raketa\BackendTestTask\Domain;

use Raketa\BackendTestTask\Domain\Model\CustomerSession;

class SessionManager
{
    public function getSession(): CustomerSession
    {
        // @fixme Вообще-то возвращает пустую строку, если сессия не запущена. Требования не ясны откуда взять и как реагировать. Оставляю as-is.
        return new CustomerSession(session_id());
    }
}