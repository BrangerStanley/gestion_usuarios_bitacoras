<?php
declare(strict_types=1);

$requestPath = rawurldecode((string)(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/'));

if (preg_match('#(?:^|/)\.{1,2}(?:/|$)#', $requestPath)
    || preg_match('#^/(?:config|controllers|database|models|storage|tests|views)(?:/|$)#i', $requestPath)
    || preg_match('#^/\.(?:git|env)(?:/|$)#i', $requestPath)) {
    http_response_code(404);
    exit('Not found');
}

$publicRoot = realpath(__DIR__ . '/public');
$candidate = realpath(__DIR__ . str_replace('/', DIRECTORY_SEPARATOR, $requestPath));
if ($publicRoot !== false
    && $candidate !== false
    && is_file($candidate)
    && str_starts_with($candidate, $publicRoot . DIRECTORY_SEPARATOR)) {
    return false;
}

$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['PHP_SELF'] = '/index.php';
require __DIR__ . '/index.php';
