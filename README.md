# Demo de gestión de usuarios y bitácora

Aplicación PHP independiente para recorrer un CRUD de cuentas y consultar una bitácora de operaciones. Conserva una presentación administrativa sencilla y una separación MVC, pero **no se conecta al sistema institucional** ni consulta personas o afiliados.

## Alcance

- Crear una cuenta directamente con un identificador ficticio, nombre de demostración y rol.
- Consultar y filtrar cuentas; cambiar nombre, rol y estado; eliminar cuentas.
- Listado, alta y edición están en vistas separadas; las altas y bajas quedan registradas en la bitácora.
- Consultar eventos de ejemplo y los eventos generados por las acciones del CRUD.
- No tiene inicio de sesión, ficha de usuario, selección de personas, afiliados, datos de contacto ni credenciales.
- Guarda los datos en `storage/usuarios_demo.sqlite`, que se crea automáticamente y no se versiona.

Utilice únicamente identificadores y nombres inventados, por ejemplo `DEMO-0001` y `Usuario Ejemplo`. Los ejemplos cargados al iniciar también son ficticios.

## Requisitos y ejecución

- PHP 8.1 o posterior con `pdo_sqlite`.
- Apache con reglas `.htaccess` habilitadas (por ejemplo, XAMPP), o el servidor integrado de PHP.

Con Apache, mantenga la carpeta dentro de `htdocs` y abra `http://localhost/para%20github/gestion_usuarios_demo/` (ajuste la URL si cambia su ubicación).

Para ejecutar el servidor integrado desde la carpeta del proyecto, use el enrutador incluido; así solo se publican el punto de entrada y los recursos de `public/`:

```powershell
& 'C:\xampp\php\php.exe' -S 127.0.0.1:8000 router.php
```

Luego visite `http://127.0.0.1:8000/`.

## Seguridad y publicación

El código puede publicarse en GitHub como proyecto demostrativo. La aplicación permite crear, modificar y borrar datos **sin autenticación**; por eso solo acepta conexiones cuyo cliente sea `localhost` (`127.0.0.1` o `::1`). No despliegue la aplicación ejecutable en un servidor accesible desde Internet ni introduzca datos reales. La sesión solo se usa para mensajes temporales y protección CSRF, no representa una identidad ni un control de acceso.

El `.htaccess` bloquea el acceso web a las capas internas y a SQLite en Apache. Mantenga esa protección al usar Apache. Para el servidor integrado de PHP, use `router.php` como se indica arriba; no lo ejecute con el document root abierto sin enrutador. En otros servidores, configure una denegación equivalente para `config/`, `controllers/`, `database/`, `models/`, `storage/`, `tests/` y `views/`, y sirva únicamente el punto de entrada y los recursos de `public/`.

Antes de subir cambios a GitHub, compruebe que no haya información real en la base local, capturas, registros ni historial de Git. La base SQLite queda excluida por `.gitignore`.

## Estructura MVC

- `controllers/`: acciones de usuarios y consultas de bitácora.
- `models/`: persistencia SQLite de cuentas y auditoría.
- `views/`: listado, formulario y bitácora.
- `database/schema.sql`: definición de tablas e índices.
- `database/seed.sql`: usuarios y eventos iniciales ficticios, aplicados una sola vez al crear la base.
- `public/`: Bootstrap, iconos y hojas de estilos visuales alineadas con el panel original; JavaScript del menú y las confirmaciones.

La demo conserva un lenguaje visual administrativo (colores, botones, formularios, tablas, cabecera fija y menú lateral) con una identidad gráfica neutra, sin logos institucionales. El pie incluye un botón que muestra la información de **Branger Stanley**, desarrollador de la demo; los datos funcionales siguen siendo ficticios.

## Validación

Ejecute las pruebas de modelo con:

```powershell
& 'C:\xampp\php\php.exe' tests\run.php
```
