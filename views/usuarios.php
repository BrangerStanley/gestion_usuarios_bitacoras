<?php
$pageTitle = 'Listado de usuarios';
require __DIR__ . '/partials/header.php';
?>
<section class="page-heading">
    <div>
        <p class="eyebrow">CUENTAS</p>
        <h1>Listado de usuarios</h1>
        <p class="muted">Consulte y administre las cuentas ficticias registradas en esta demostración.</p>
    </div>
    <a class="btn btn-celeste btn-sm" href="index.php?page=crear">
        <i class="bi bi-person-plus-fill me-1" aria-hidden="true"></i>Crear cuenta
    </a>
</section>

<section class="card shadow-sm border-0 rounded-3 list-card">
    <div class="card-body p-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 pb-2 mb-3 border-bottom">
            <h2 class="h6 mb-0 fw-bold text-secondary text-uppercase">
                <i class="bi bi-people me-2" aria-hidden="true"></i>Usuarios registrados
            </h2>
            <form class="search-form" method="get" action="index.php">
                <input type="hidden" name="page" value="usuarios">
                <label class="sr-only" for="buscar">Buscar usuario</label>
                <input id="buscar" name="buscar" class="form-control form-control-sm" value="<?= escapar($search) ?>" placeholder="Buscar nombre o cédula">
                <button class="btn btn-primary btn-sm" type="submit"><i class="bi bi-search me-1" aria-hidden="true"></i>Buscar</button>
            </form>
        </div>
        <p class="text-muted small mb-3">Registros de demostración guardados en SQLite local.</p>
        <div class="table-wrap">
            <table class="table table-sm table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Usuario</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Creación</th>
                        <th class="actions-heading">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($users === []): ?>
                    <tr><td class="empty-row" colspan="5"><i class="bi bi-inbox me-1" aria-hidden="true"></i>No hay cuentas que coincidan con la búsqueda.</td></tr>
                <?php else: ?>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td>
                                <span class="user-name"><?= escapar($user['nombre']) ?></span>
                                <span class="user-id"><?= escapar($user['cedula']) ?></span>
                            </td>
                            <td><?= escapar($user['rol']) ?></td>
                            <td><span class="status status-<?= strtolower(escapar($user['estado'])) ?>"><?= escapar($user['estado']) ?></span></td>
                            <td class="date-cell"><?= escapar(date('d/m/Y H:i', strtotime($user['created_at'] . ' UTC'))) ?></td>
                            <td class="row-actions">
                                <a class="btn btn-outline-primary btn-sm" href="index.php?page=editar&amp;id=<?= (int)$user['id'] ?>">
                                    <i class="bi bi-pencil me-1" aria-hidden="true"></i>Editar
                                </a>
                                <form method="post" action="index.php?page=usuarios" data-confirm="¿Eliminar esta cuenta de demostración? La acción quedará en la bitácora.">
                                    <input type="hidden" name="_csrf" value="<?= escapar(csrfToken()) ?>">
                                    <input type="hidden" name="action" value="eliminar">
                                    <input type="hidden" name="id" value="<?= (int)$user['id'] ?>">
                                    <button class="btn btn-outline-danger btn-sm" type="submit"><i class="bi bi-trash me-1" aria-hidden="true"></i>Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="table-foot mt-3">La ficha personal y los datos de afiliación no forman parte de esta demo.</div>
    </div>
</section>
<?php require __DIR__ . '/partials/footer.php'; ?>
