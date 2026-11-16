<?php
$pageTitle = $editingUser ? 'Editar usuario' : 'Crear cuenta';
require __DIR__ . '/partials/header.php';
$roles = ['Administrador demo', 'Operador demo', 'Consulta demo'];
?>
<section class="page-heading">
    <div>
        <p class="eyebrow">CUENTAS</p>
        <h1><?= $editingUser ? 'Editar usuario' : 'Crear cuenta de usuario' ?></h1>
        <p class="muted"><?= $editingUser ? 'Actualice los datos de esta cuenta ficticia.' : 'Registre una cuenta directamente, sin buscar personas ni afiliados.' ?></p>
    </div>
    <a class="btn btn-outline-secondary btn-sm" href="index.php?page=usuarios">
        <i class="bi bi-arrow-left me-1" aria-hidden="true"></i>Volver al listado
    </a>
</section>

<section class="card shadow-sm border-0 rounded-3 form-card">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center pb-2 mb-3 border-bottom">
            <h2 class="h6 mb-0 fw-bold text-secondary text-uppercase">
                <i class="bi <?= $editingUser ? 'bi-pencil-square' : 'bi-person-plus-fill' ?> me-2" aria-hidden="true"></i>
                <?= $editingUser ? 'Datos de la cuenta' : 'Datos de la nueva cuenta' ?>
            </h2>
        </div>

        <form method="post" action="index.php?page=<?= $editingUser ? 'editar' : 'crear' ?>" class="user-form">
            <input type="hidden" name="_csrf" value="<?= escapar(csrfToken()) ?>">
            <input type="hidden" name="action" value="<?= $editingUser ? 'actualizar' : 'crear' ?>">
            <?php if ($editingUser): ?>
                <input type="hidden" name="id" value="<?= (int)$editingUser['id'] ?>">
            <?php endif; ?>

            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <label for="cedula" class="form-label small text-muted fw-bold mb-1">Cédula / identificador ficticio</label>
                    <?php if ($editingUser): ?>
                        <input id="cedula" class="form-control readonly" value="<?= escapar($editingUser['cedula']) ?>" readonly>
                        <div class="form-text field-help">El identificador no se puede cambiar después del alta.</div>
                    <?php else: ?>
                        <input id="cedula" name="cedula" class="form-control" maxlength="20" minlength="4" pattern="[A-Za-z0-9.-]+" placeholder="Ej.: DEMO-0004" autocomplete="off" required>
                        <div class="form-text field-help">Use un valor inventado; no se consulta ninguna fuente externa.</div>
                    <?php endif; ?>
                </div>

                <div class="col-12 col-md-6">
                    <label for="nombre" class="form-label small text-muted fw-bold mb-1">Nombre de demostración</label>
                    <input id="nombre" name="nombre" class="form-control" maxlength="80" minlength="3" placeholder="Ej.: Usuario Ejemplo" value="<?= escapar($editingUser['nombre'] ?? '') ?>" required>
                </div>

                <div class="col-12 col-md-6">
                    <label for="rol" class="form-label small text-muted fw-bold mb-1">Rol</label>
                    <select id="rol" name="rol" class="form-select" required>
                        <option value="">Seleccione un rol demo</option>
                        <?php foreach ($roles as $rol): ?>
                            <option value="<?= escapar($rol) ?>" <?= ($editingUser['rol'] ?? '') === $rol ? 'selected' : '' ?>><?= escapar($rol) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <?php if ($editingUser): ?>
                    <div class="col-12 col-md-6">
                        <label for="estado" class="form-label small text-muted fw-bold mb-1">Estado</label>
                        <select id="estado" name="estado" class="form-select">
                            <option value="Activo" <?= $editingUser['estado'] === 'Activo' ? 'selected' : '' ?>>Activo</option>
                            <option value="Inactivo" <?= $editingUser['estado'] === 'Inactivo' ? 'selected' : '' ?>>Inactivo</option>
                        </select>
                    </div>
                <?php endif; ?>

                <div class="col-12">
                    <div class="form-actions d-flex justify-content-end gap-2 pt-3 border-top">
                        <?php if (!$editingUser): ?>
                            <button class="btn btn-secondary btn-sm" type="reset">Limpiar</button>
                        <?php endif; ?>
                        <button class="btn btn-celeste btn-sm px-4" type="submit">
                            <i class="bi <?= $editingUser ? 'bi-check-lg' : 'bi-person-plus-fill' ?> me-1" aria-hidden="true"></i>
                            <?= $editingUser ? 'Guardar cambios' : 'Crear cuenta' ?>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
<?php require __DIR__ . '/partials/footer.php'; ?>
