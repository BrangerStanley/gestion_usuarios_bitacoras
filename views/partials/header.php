<?php
$pageTitle = $pageTitle ?? 'Administración demo';
$activePage = $activePage ?? 'usuarios';
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#3678a8">
    <title><?= escapar($pageTitle) ?> | CRUD demo</title>
    <link rel="stylesheet" href="public/css/bootstrap.min.css">
    <link rel="stylesheet" href="public/css/icons/bootstrap-icons.css">
    <link rel="stylesheet" href="public/css/Global.css">
    <link rel="stylesheet" href="public/css/responsive.css">
    <link rel="stylesheet" href="public/css/admin.css">
    <link rel="stylesheet" href="public/css/admin_ui.css">
    <link rel="stylesheet" href="public/css/app.css">
    <link rel="stylesheet" href="public/css/equipo.css">
    <script src="public/js/app.js" defer></script>
</head>
<body class="iaa-layout">
<header class="header">
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top border-bottom">
        <div class="container-fluid px-4 d-flex justify-content-between align-items-center">
            <div class="header-left">
                <button type="button" class="iaa-menu-toggle" id="menuToggle" aria-label="Abrir navegación" aria-controls="sidebar" aria-expanded="false">
                    <span></span><span></span><span></span>
                </button>
                <a class="logo-area d-flex align-items-center text-decoration-none" href="index.php" aria-label="Panel de gestión demo">
                    <span class="demo-brand-mark" aria-hidden="true"><i class="bi bi-person-gear"></i></span>
                    <span class="logo-area-text ms-2">
                        <h1 class="h5 mb-0 fw-bold">Gestión de usuarios</h1>
                        <p class="small text-muted mb-0">Administración CRUD de demostración</p>
                    </span>
                </a>
            </div>
            <span class="badge bg-primary p-2"><i class="bi bi-shield-check me-1" aria-hidden="true"></i>Modo demo</span>
        </div>
    </nav>
</header>
<div class="iaa-sidebar-backdrop" id="sidebarBackdrop" aria-hidden="true"></div>
<main class="d-flex demo-main">
    <aside class="barra-lateral py-3" id="sidebar" aria-label="Navegación principal">
        <div class="iaa-sidebar-cabecera">
            <span class="iaa-sidebar-titulo">Administración</span>
            <button type="button" class="iaa-sidebar-cerrar" id="sidebarClose" aria-label="Cerrar navegación">
                <i class="bi bi-x-lg" aria-hidden="true"></i>
            </button>
        </div>
        <a class="bl <?= $activePage === 'usuarios' ? 'active' : '' ?>" href="index.php?page=usuarios">
            <i class="bi bi-people-fill me-2" aria-hidden="true"></i> Usuarios
        </a>
        <a class="bl <?= $activePage === 'crear' ? 'active' : '' ?>" href="index.php?page=crear">
            <i class="bi bi-person-plus-fill me-2" aria-hidden="true"></i> Crear cuenta
        </a>
        <a class="bl <?= $activePage === 'bitacora' ? 'active' : '' ?>" href="index.php?page=bitacora">
            <i class="bi bi-journal-text me-2" aria-hidden="true"></i> Bitácora
        </a>
        <div class="demo-sidebar-tip">
            <strong><i class="bi bi-info-circle me-1" aria-hidden="true"></i> Solo demostración</strong>
            <span>No ingrese datos reales.</span>
        </div>
    </aside>
    <div class="flex-grow-1 p-4 mt-3 ms-3 me-3 demo-content">
        <div class="demo-banner">
            <i class="bi bi-info-circle-fill" aria-hidden="true"></i>
            <span><strong>Modo demostración sin inicio de sesión.</strong> Use identificadores inventados; esta aplicación no consulta datos externos.</span>
        </div>
        <?php if (!empty($notice)): ?>
            <div class="alert alert-<?= escapar($notice['type'] ?? 'info') ?> demo-alert" role="status">
                <?= escapar($notice['message'] ?? '') ?>
            </div>
        <?php endif; ?>
