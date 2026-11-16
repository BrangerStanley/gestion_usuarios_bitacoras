CREATE TABLE IF NOT EXISTS usuarios (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    cedula TEXT NOT NULL COLLATE NOCASE UNIQUE,
    nombre TEXT NOT NULL,
    rol TEXT NOT NULL CHECK (rol IN ('Administrador demo', 'Operador demo', 'Consulta demo')),
    estado TEXT NOT NULL DEFAULT 'Activo' CHECK (estado IN ('Activo', 'Inactivo')),
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS bitacora (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_evento_demo TEXT UNIQUE,
    fecha TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actor TEXT NOT NULL,
    accion TEXT NOT NULL,
    categoria TEXT NOT NULL CHECK (categoria IN ('creacion', 'edicion', 'eliminacion')),
    usuario_nombre TEXT NOT NULL,
    usuario_cedula TEXT NOT NULL,
    detalle TEXT NOT NULL
);

CREATE INDEX IF NOT EXISTS idx_usuarios_nombre ON usuarios(nombre);
CREATE INDEX IF NOT EXISTS idx_bitacora_fecha ON bitacora(fecha DESC);
