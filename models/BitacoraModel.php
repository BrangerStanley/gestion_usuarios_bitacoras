<?php
declare(strict_types=1);

final class BitacoraModel
{
    public function __construct(private PDO $db)
    {
    }

    public function registrar(
        string $action,
        string $category,
        string $userName,
        string $cedula,
        string $detail
    ): void {
        $stmt = $this->db->prepare(
            'INSERT INTO bitacora (actor, accion, categoria, usuario_nombre, usuario_cedula, detalle)
             VALUES (:actor, :accion, :categoria, :nombre, :cedula, :detalle)'
        );
        $stmt->execute([
            ':actor' => 'Administrador de demostración',
            ':accion' => $action,
            ':categoria' => $category,
            ':nombre' => $userName,
            ':cedula' => $cedula,
            ':detalle' => $detail,
        ]);
    }

    public function listar(string $search = '', string $category = ''): array
    {
        $conditions = [];
        $params = [];

        if ($search !== '') {
            $conditions[] = '(usuario_nombre LIKE :search OR usuario_cedula LIKE :search OR detalle LIKE :search OR actor LIKE :search)';
            $params[':search'] = '%' . $search . '%';
        }
        if (in_array($category, ['creacion', 'edicion', 'eliminacion'], true)) {
            $conditions[] = 'categoria = :category';
            $params[':category'] = $category;
        }

        $sql = 'SELECT id, fecha, actor, accion, categoria, usuario_nombre, usuario_cedula, detalle
                FROM bitacora';
        if ($conditions !== []) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }
        $sql .= ' ORDER BY fecha DESC, id DESC LIMIT 100';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
