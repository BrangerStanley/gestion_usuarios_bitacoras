<?php
declare(strict_types=1);

final class UsuarioModel
{
    public function __construct(private PDO $db)
    {
    }

    public function listar(string $search = ''): array
    {
        if ($search === '') {
            $stmt = $this->db->query(
                'SELECT id, cedula, nombre, rol, estado, created_at, updated_at
                 FROM usuarios ORDER BY id DESC'
            );
            return $stmt->fetchAll();
        }

        $stmt = $this->db->prepare(
            'SELECT id, cedula, nombre, rol, estado, created_at, updated_at
             FROM usuarios
             WHERE cedula LIKE :search OR nombre LIKE :search OR rol LIKE :search
             ORDER BY id DESC'
        );
        $stmt->execute([':search' => '%' . $search . '%']);
        return $stmt->fetchAll();
    }

    public function buscar(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT id, cedula, nombre, rol, estado, created_at, updated_at
             FROM usuarios WHERE id = :id'
        );
        $stmt->execute([':id' => $id]);
        $user = $stmt->fetch();
        return is_array($user) ? $user : null;
    }

    public function crear(string $cedula, string $nombre, string $rol): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO usuarios (cedula, nombre, rol) VALUES (:cedula, :nombre, :rol)'
        );
        $stmt->execute([
            ':cedula' => $cedula,
            ':nombre' => $nombre,
            ':rol' => $rol,
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function actualizar(int $id, string $nombre, string $rol, string $estado): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE usuarios
             SET nombre = :nombre, rol = :rol, estado = :estado, updated_at = CURRENT_TIMESTAMP
             WHERE id = :id'
        );
        $stmt->execute([
            ':nombre' => $nombre,
            ':rol' => $rol,
            ':estado' => $estado,
            ':id' => $id,
        ]);
        return $stmt->rowCount() === 1;
    }

    public function eliminar(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM usuarios WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount() === 1;
    }
}
