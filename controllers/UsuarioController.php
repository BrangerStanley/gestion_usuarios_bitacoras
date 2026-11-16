<?php
declare(strict_types=1);

require_once __DIR__ . '/../models/UsuarioModel.php';
require_once __DIR__ . '/../models/BitacoraModel.php';

final class UsuarioController
{
    private UsuarioModel $usuarios;
    private BitacoraModel $bitacora;

    private const ROLES = ['Administrador demo', 'Operador demo', 'Consulta demo'];

    public function __construct(private PDO $db)
    {
        $this->usuarios = new UsuarioModel($db);
        $this->bitacora = new BitacoraModel($db);
    }

    public function listar(string $search = ''): array
    {
        return $this->usuarios->listar($search);
    }

    public function buscar(int $id): ?array
    {
        return $this->usuarios->buscar($id);
    }

    public function procesar(array $input): never
    {
        $action = is_string($input['action'] ?? null) ? $input['action'] : '';
        $destination = $action === 'crear' ? 'crear' : 'usuarios';
        if ($action === 'actualizar') {
            $editId = filter_var($input['id'] ?? null, FILTER_VALIDATE_INT);
            if ($editId !== false && $editId > 0) {
                $destination = 'editar&id=' . (int)$editId;
            }
        }

        if (!csrfValido($input['_csrf'] ?? null)) {
            guardarFlash('danger', 'La solicitud venció o no es válida. Intente nuevamente.');
            redirigir('index.php?page=' . $destination);
        }

        try {
            if ($action === 'crear') {
                $this->crear($input);
                guardarFlash('success', 'La cuenta de demostración se creó correctamente.');
                $destination = 'usuarios';
            } elseif ($action === 'actualizar') {
                $this->actualizar($input);
                guardarFlash('success', 'La cuenta de demostración se actualizó correctamente.');
                $destination = 'usuarios';
            } elseif ($action === 'eliminar') {
                $this->eliminar($input);
                guardarFlash('success', 'La cuenta de demostración se eliminó correctamente.');
            } else {
                guardarFlash('danger', 'La acción solicitada no es válida.');
            }
        } catch (InvalidArgumentException $e) {
            guardarFlash('warning', $e->getMessage());
        } catch (PDOException $e) {
            if ($e->getCode() === '23000' || (int)($e->errorInfo[1] ?? 0) === 19) {
                guardarFlash('warning', 'Ya existe una cuenta con ese identificador.');
            } else {
                error_log('UsuarioController: ' . $e->getMessage());
                guardarFlash('danger', 'No se pudo guardar la cuenta. Revise el registro local.');
            }
        } catch (Throwable $e) {
            error_log('UsuarioController: ' . $e->getMessage());
            guardarFlash('danger', 'No se pudo completar la operación. Revise el registro local.');
        }

        redirigir('index.php?page=' . $destination);
    }

    private function crear(array $input): void
    {
        $cedula = $this->texto($input, 'cedula');
        $nombre = $this->normalizarNombre($this->texto($input, 'nombre'));
        $rol = $this->texto($input, 'rol');
        $this->validarIdentidad($cedula, $nombre, $rol);

        $this->db->beginTransaction();
        try {
            $this->usuarios->crear($cedula, $nombre, $rol);
            $this->bitacora->registrar(
                'Cuenta creada',
                'creacion',
                $nombre,
                $cedula,
                'Se creó la cuenta ficticia con rol ' . $rol . '.'
            );
            $this->db->commit();
        } catch (Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $e;
        }
    }

    private function actualizar(array $input): void
    {
        $id = $this->id($input);
        $actual = $this->usuarios->buscar($id);
        if ($actual === null) {
            throw new InvalidArgumentException('No se encontró la cuenta que desea actualizar.');
        }

        $nombre = $this->normalizarNombre($this->texto($input, 'nombre'));
        $rol = $this->texto($input, 'rol');
        $estado = $this->texto($input, 'estado');
        if (strlen($nombre) < 3 || strlen($nombre) > 160 || preg_match('/[\x00-\x1F\x7F]/', $nombre)) {
            throw new InvalidArgumentException('Escriba un nombre de demostración de 3 a 80 caracteres.');
        }
        if (!in_array($rol, self::ROLES, true) || !in_array($estado, ['Activo', 'Inactivo'], true)) {
            throw new InvalidArgumentException('Seleccione un rol y estado de demostración válidos.');
        }

        $this->db->beginTransaction();
        try {
            if (!$this->usuarios->actualizar($id, $nombre, $rol, $estado)) {
                throw new InvalidArgumentException('No se encontró la cuenta que desea actualizar.');
            }
            $this->bitacora->registrar(
                'Cuenta actualizada',
                'edicion',
                $nombre,
                (string)$actual['cedula'],
                'Se actualizó la cuenta de demostración (rol: ' . $rol . ', estado: ' . $estado . ').'
            );
            $this->db->commit();
        } catch (Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $e;
        }
    }

    private function eliminar(array $input): void
    {
        $id = $this->id($input);
        $actual = $this->usuarios->buscar($id);
        if ($actual === null) {
            throw new InvalidArgumentException('No se encontró la cuenta que desea eliminar.');
        }

        $this->db->beginTransaction();
        try {
            if (!$this->usuarios->eliminar($id)) {
                throw new InvalidArgumentException('No se encontró la cuenta que desea eliminar.');
            }
            $this->bitacora->registrar(
                'Cuenta eliminada',
                'eliminacion',
                (string)$actual['nombre'],
                (string)$actual['cedula'],
                'Se eliminó la cuenta ficticia de demostración.'
            );
            $this->db->commit();
        } catch (Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $e;
        }
    }

    private function validarIdentidad(string $cedula, string $nombre, string $rol): void
    {
        if (strlen($cedula) < 4 || strlen($cedula) > 20 || !preg_match('/^[A-Za-z0-9.-]+$/', $cedula)) {
            throw new InvalidArgumentException('Ingrese un identificador ficticio válido (4 a 20 letras o números).');
        }
        if (strlen($nombre) < 3 || strlen($nombre) > 160 || preg_match('/[\x00-\x1F\x7F]/', $nombre)) {
            throw new InvalidArgumentException('Escriba un nombre de demostración de 3 a 80 caracteres.');
        }
        if (!in_array($rol, self::ROLES, true)) {
            throw new InvalidArgumentException('Seleccione un rol de demostración válido.');
        }
    }

    private function id(array $input): int
    {
        $id = filter_var($input['id'] ?? null, FILTER_VALIDATE_INT);
        if ($id === false || $id <= 0) {
            throw new InvalidArgumentException('La cuenta seleccionada no es válida.');
        }
        return $id;
    }

    private function texto(array $input, string $key): string
    {
        $value = $input[$key] ?? '';
        return is_string($value) ? trim($value) : '';
    }

    private function normalizarNombre(string $nombre): string
    {
        $normalizado = preg_replace('/\s+/u', ' ', trim($nombre));
        return is_string($normalizado) ? $normalizado : '';
    }
}
