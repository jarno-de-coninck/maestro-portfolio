<?php

namespace Framework;

class Session
{
    private bool $secure = true;

    public function __construct()
    {
    }

    public function set(string $key, string|int $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function get(string $key): string|null
    {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
        return null;
    }

    /**
     * @param string $key
     * @param array<string, mixed> $data
     */
    public function setMany(string $key, array $data): void
    {
        $_SESSION[$key] = $data;
    }

    /**
     * @param string $key
     * @return array<string, mixed>|null
     */
    public function getMany(string $key): ?array
    {
        if (isset($_SESSION[$key]) && is_array($_SESSION[$key])) {
            return $_SESSION[$key];
        }
        return null;
    }

    /**
     * @param class-string $key
     * @param object $value
     */
    public function setAttribute(string $key, object $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * @param class-string $key
     * @return object|null
     */
    public function getAttribute(string $key): object|null
    {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
        return null;
    }

    /**
     * @return array<string, string>
     */
    public function getAll(): array
    {
        $session = $_SESSION;
        unset($_SESSION['flash']);
        return $session;
    }

    public function clear(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public function destroy(): void
    {
        session_destroy();
        $this->sessionStart($this->secure);
    }

    public function sessionStart(bool $secure = true): void
    {
        $this->secure = $secure;
        if (session_status() === PHP_SESSION_NONE) {
            session_name('maestro');

            session_set_cookie_params([
                'path'     => '/',
                'domain'   => '',
                'secure'   => $this->secure,
                'httponly' => true,
                'samesite' => 'Strict',
            ]);

            session_start();
        }
    }

    public function flash(string $key, string $value): void
    {
        $_SESSION['flash'][$key] = $value;
    }

    /**
     * @return array<string, string>
     */
    public function getFlash(): array
    {
        $flash = $_SESSION['flash'] ?? [];
        unset($_SESSION['flash']);
        return $flash;
    }
}
