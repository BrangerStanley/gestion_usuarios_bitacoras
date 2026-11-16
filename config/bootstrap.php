<?php
declare(strict_types=1);

function iniciarSesionDemo(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    session_set_cookie_params([
        'httponly' => true,
        'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'samesite' => 'Lax',
        'path' => '/',
    ]);
    session_start();

    if (!isset($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }
}

function exigirLocalhost(): void
{
    $ip = (string)($_SERVER['REMOTE_ADDR'] ?? '');
    if (!in_array($ip, ['127.0.0.1', '::1'], true)) {
        http_response_code(403);
        header('Content-Type: text/plain; charset=utf-8');
        exit('Esta demostración solo está disponible desde localhost.');
    }
}

function enviarCabecerasDemo(): void
{
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: DENY');
    header('Referrer-Policy: no-referrer');
    header("Content-Security-Policy: default-src 'self'; style-src 'self'; script-src 'self'; img-src 'self' data:; base-uri 'self'; form-action 'self'; frame-ancestors 'none'");
}

function csrfToken(): string
{
    return (string)($_SESSION['_csrf'] ?? '');
}

function csrfValido(mixed $token): bool
{
    return is_string($token) && $token !== '' && hash_equals(csrfToken(), $token);
}

function escapar(mixed $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function guardarFlash(string $type, string $message): void
{
    $_SESSION['_flash'] = ['type' => $type, 'message' => $message];
}

function consumirFlash(): ?array
{
    $flash = $_SESSION['_flash'] ?? null;
    unset($_SESSION['_flash']);
    return is_array($flash) ? $flash : null;
}

function redirigir(string $url): never
{
    header('Location: ' . $url, true, 303);
    exit;
}
