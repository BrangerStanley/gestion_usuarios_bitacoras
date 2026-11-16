<?php
declare(strict_types=1);

require_once __DIR__ . '/config/bootstrap.php';
iniciarSesionDemo();
exigirLocalhost();
enviarCabecerasDemo();

require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/controllers/UsuarioController.php';
require_once __DIR__ . '/controllers/BitacoraController.php';

$pageParam = is_string($_GET['page'] ?? null) ? $_GET['page'] : 'usuarios';
$allowedPages = ['usuarios', 'crear', 'editar', 'bitacora'];
$page = in_array($pageParam, $allowedPages, true) ? $pageParam : 'usuarios';

try {
    $db = Database::connection();
    $usuarioController = new UsuarioController($db);
    $bitacoraController = new BitacoraController($db);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $usuarioController->procesar($_POST);
    }

    $notice = consumirFlash();

    if ($page === 'bitacora') {
        $search = is_string($_GET['buscar'] ?? null) ? trim($_GET['buscar']) : '';
        $category = is_string($_GET['tipo'] ?? null) ? trim($_GET['tipo']) : '';
        $events = $bitacoraController->listar($search, $category);
        $activePage = 'bitacora';
        require __DIR__ . '/views/bitacoras.php';
        exit;
    }

    if ($page === 'crear') {
        $editingUser = null;
        $activePage = 'crear';
        require __DIR__ . '/views/crear_usuario.php';
        exit;
    }

    if ($page === 'editar') {
        $editingId = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
        $editingUser = $editingId && $editingId > 0 ? $usuarioController->buscar((int)$editingId) : null;
        if ($editingUser === null) {
            guardarFlash('warning', 'No se encontró la cuenta que desea editar.');
            redirigir('index.php?page=usuarios');
        }
        $activePage = 'crear';
        require __DIR__ . '/views/crear_usuario.php';
        exit;
    }

    $search = is_string($_GET['buscar'] ?? null) ? trim($_GET['buscar']) : '';
    $users = $usuarioController->listar($search);
    $activePage = 'usuarios';
    require __DIR__ . '/views/usuarios.php';
} catch (Throwable $e) {
    error_log('gestion_usuarios_demo: ' . $e->getMessage());
    http_response_code(500);
    $notice = [
        'type' => 'danger',
        'message' => 'No se pudo cargar la demostración. Revise la configuración local de PHP y SQLite.',
    ];
    $activePage = $page === 'editar' ? 'crear' : $page;
    require __DIR__ . '/views/error.php';
}
