<?php
$pageTitle = 'Bitácora de operaciones';
require __DIR__ . '/partials/header.php';
$labels = [
    'creacion' => ['Creación', 'created', 'bi-person-plus'],
    'edicion' => ['Edición', 'updated', 'bi-pencil-square'],
    'eliminacion' => ['Eliminación', 'deleted', 'bi-trash'],
];
?>
<section class="page-heading">
    <div>
        <p class="eyebrow">AUDITORÍA DEMO</p>
        <h1>Bitácora de operaciones</h1>
        <p class="muted">Ejemplos iniciales y registro de altas, ediciones y eliminaciones realizadas aquí.</p>
    </div>
    <span class="count-pill"><i class="bi bi-journal-text" aria-hidden="true"></i><?= count($events) ?> eventos</span>
</section>

<section class="card shadow-sm border-0 rounded-3 audit-card">
    <div class="card-body p-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 pb-2 mb-3 border-bottom">
            <h2 class="h6 mb-0 fw-bold text-secondary text-uppercase">
                <i class="bi bi-clock-history me-2" aria-hidden="true"></i>Actividad reciente
            </h2>
            <form class="search-form audit-search" method="get" action="index.php">
                <input type="hidden" name="page" value="bitacora">
                <label class="sr-only" for="buscar">Buscar eventos</label>
                <input id="buscar" name="buscar" class="form-control form-control-sm" value="<?= escapar($search) ?>" placeholder="Buscar nombre o identificador">
                <label class="sr-only" for="tipo">Tipo de evento</label>
                <select id="tipo" name="tipo" class="form-select form-select-sm">
                    <option value="">Todos los tipos</option>
                    <?php foreach ($labels as $value => [$label]): ?>
                        <option value="<?= escapar($value) ?>" <?= $category === $value ? 'selected' : '' ?>><?= escapar($label) ?></option>
                    <?php endforeach; ?>
                </select>
                <button class="btn btn-primary btn-sm" type="submit"><i class="bi bi-funnel me-1" aria-hidden="true"></i>Filtrar</button>
            </form>
        </div>
        <p class="text-muted small mb-3">Se muestran hasta 100 eventos, con los datos ficticios asociados a cada operación.</p>
        <div class="table-wrap">
            <table class="table table-sm table-hover align-middle audit-table">
                <thead class="table-light">
                    <tr>
                        <th>Fecha</th>
                        <th>Acción</th>
                        <th>Cuenta</th>
                        <th>Registrado por</th>
                        <th>Detalle</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($events === []): ?>
                    <tr><td class="empty-row" colspan="5"><i class="bi bi-inbox me-1" aria-hidden="true"></i>No hay eventos que coincidan con los filtros.</td></tr>
                <?php else: ?>
                    <?php foreach ($events as $event): ?>
                        <?php [$label, $kind, $icon] = $labels[$event['categoria']] ?? ['Evento', 'updated', 'bi-journal-text']; ?>
                        <tr>
                            <td class="date-cell"><?= escapar(date('d/m/Y H:i', strtotime($event['fecha'] . ' UTC'))) ?></td>
                            <td><span class="event-badge event-<?= escapar($kind) ?>"><i class="bi <?= escapar($icon) ?> me-1" aria-hidden="true"></i><?= escapar($event['accion']) ?></span></td>
                            <td>
                                <span class="user-name"><?= escapar($event['usuario_nombre']) ?></span>
                                <span class="user-id"><?= escapar($event['usuario_cedula']) ?></span>
                            </td>
                            <td><?= escapar($event['actor']) ?></td>
                            <td class="audit-detail"><?= escapar($event['detalle']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="table-foot mt-3">La bitácora es local y no registra direcciones IP, sesiones ni datos de autenticación.</div>
    </div>
</section>
<?php require __DIR__ . '/partials/footer.php'; ?>
