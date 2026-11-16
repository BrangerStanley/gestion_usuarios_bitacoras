INSERT OR IGNORE INTO usuarios (id, cedula, nombre, rol, estado, created_at, updated_at) VALUES
    (1, 'DEMO-0001', 'Alex Ejemplo', 'Administrador demo', 'Activo', datetime('now', '-3 day'), datetime('now', '-3 day')),
    (2, 'DEMO-0002', 'Dana Prueba', 'Operador demo', 'Activo', datetime('now', '-2 day'), datetime('now', '-2 day')),
    (3, 'DEMO-0003', 'Sam Muestra', 'Consulta demo', 'Inactivo', datetime('now', '-1 day'), datetime('now', '-1 day'));

INSERT OR IGNORE INTO bitacora
    (id_evento_demo, fecha, actor, accion, categoria, usuario_nombre, usuario_cedula, detalle)
VALUES
    ('demo-create-1', datetime('now', '-3 day'), 'Administrador de demostración', 'Cuenta creada', 'creacion', 'Alex Ejemplo', 'DEMO-0001', 'Alta de cuenta de ejemplo.'),
    ('demo-update-1', datetime('now', '-2 day'), 'Administrador de demostración', 'Cuenta actualizada', 'edicion', 'Dana Prueba', 'DEMO-0002', 'Se actualizó el rol de demostración.'),
    ('demo-delete-1', datetime('now', '-1 day'), 'Administrador de demostración', 'Cuenta eliminada', 'eliminacion', 'Taylor Eliminado', 'DEMO-0099', 'Eliminación de una cuenta ficticia de ejemplo.'),
    ('demo-create-2', datetime('now', '-12 hour'), 'Administrador de demostración', 'Cuenta creada', 'creacion', 'Sam Muestra', 'DEMO-0003', 'Alta de cuenta de ejemplo.');
