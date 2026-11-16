<?php
$pageTitle = 'Error de demostración';
require __DIR__ . '/partials/header.php';
?>
<section class="page-heading">
    <div>
        <p class="eyebrow">CRUD DEMO</p>
        <h1>No se pudo abrir la administración</h1>
        <p class="muted"><?= escapar($notice['message'] ?? 'Revise la configuración local e inténtelo de nuevo.') ?></p>
    </div>
</section>
<?php require __DIR__ . '/partials/footer.php'; ?>
