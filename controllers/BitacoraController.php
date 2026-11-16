<?php
declare(strict_types=1);

require_once __DIR__ . '/../models/BitacoraModel.php';

final class BitacoraController
{
    private BitacoraModel $bitacora;

    public function __construct(PDO $db)
    {
        $this->bitacora = new BitacoraModel($db);
    }

    public function listar(string $search = '', string $category = ''): array
    {
        return $this->bitacora->listar($search, $category);
    }
}
