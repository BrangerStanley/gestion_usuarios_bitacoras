<?php
declare(strict_types=1);

require_once __DIR__ . '/../models/UsuarioModel.php';
require_once __DIR__ . '/../models/BitacoraModel.php';

$db = new PDO('sqlite::memory:', null, null, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);
$schema = file_get_contents(__DIR__ . '/../database/schema.sql');
$seed = file_get_contents(__DIR__ . '/../database/seed.sql');
if ($schema === false || $seed === false) {
    throw new RuntimeException('No se pudieron leer el esquema o los datos iniciales de prueba.');
}
$db->exec($schema);
$db->exec($seed);

$users = new UsuarioModel($db);
$audit = new BitacoraModel($db);
$identifier = 'TEST-' . strtoupper(bin2hex(random_bytes(4)));

$db->beginTransaction();
$id = $users->crear($identifier, 'Nombre de Prueba', 'Operador demo');
$audit->registrar('Cuenta creada', 'creacion', 'Nombre de Prueba', $identifier, 'Alta de prueba.');
$db->commit();

$created = $users->buscar($id);
if (!$created || $created['cedula'] !== $identifier) {
    throw new RuntimeException('Fallo al crear y consultar una cuenta.');
}

$db->beginTransaction();
$users->actualizar($id, 'Nombre Actualizado', 'Consulta demo', 'Inactivo');
$audit->registrar('Cuenta actualizada', 'edicion', 'Nombre Actualizado', $identifier, 'Edición de prueba.');
$db->commit();

$updated = $users->buscar($id);
if (!$updated || $updated['nombre'] !== 'Nombre Actualizado' || $updated['estado'] !== 'Inactivo') {
    throw new RuntimeException('Fallo al actualizar una cuenta.');
}

$db->beginTransaction();
$users->eliminar($id);
$audit->registrar('Cuenta eliminada', 'eliminacion', 'Nombre Actualizado', $identifier, 'Baja de prueba.');
$db->commit();

if ($users->buscar($id) !== null) {
    throw new RuntimeException('Fallo al eliminar una cuenta.');
}

$events = $audit->listar($identifier);
if (count($events) !== 3) {
    throw new RuntimeException('La bitácora no conservó los eventos de alta, edición y baja.');
}

try {
    $users->crear('DEMO-0001', 'Duplicado de Prueba', 'Consulta demo');
    throw new RuntimeException('No se rechazó un identificador duplicado.');
} catch (PDOException $e) {
    if ((int)($e->errorInfo[1] ?? 0) !== 19) {
        throw $e;
    }
}

$sampleId = 1;
if (!$users->eliminar($sampleId)) {
    throw new RuntimeException('Fallo al eliminar una cuenta de ejemplo.');
}
$db->exec($schema);
if ($users->buscar($sampleId) !== null) {
    throw new RuntimeException('La cuenta de ejemplo reapareció al ejecutar de nuevo el esquema.');
}

echo "OK: CRUD, auditoría, duplicados y eliminación persistente de ejemplos.\n";
