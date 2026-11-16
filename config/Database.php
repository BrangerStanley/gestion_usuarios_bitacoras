<?php
declare(strict_types=1);

final class Database
{
    private static ?PDO $connection = null;

    public static function connection(): PDO
    {
        if (self::$connection instanceof PDO) {
            return self::$connection;
        }

        $storage = dirname(__DIR__) . '/storage';
        if (!is_dir($storage) && !mkdir($storage, 0700, true) && !is_dir($storage)) {
            throw new RuntimeException('No se pudo crear el directorio de datos local.');
        }

        $pdo = new PDO('sqlite:' . $storage . '/usuarios_demo.sqlite', null, null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
        $pdo->exec('PRAGMA foreign_keys = ON');
        $pdo->exec('PRAGMA busy_timeout = 5000');

        $schemaPath = dirname(__DIR__) . '/database/schema.sql';
        $schema = file_get_contents($schemaPath);
        if ($schema === false) {
            throw new RuntimeException('No se pudo leer el esquema SQLite.');
        }
        $pdo->exec($schema);

        $schemaVersion = (int)$pdo->query('PRAGMA user_version')->fetchColumn();
        if ($schemaVersion < 1) {
            $seedPath = dirname(__DIR__) . '/database/seed.sql';
            $seed = file_get_contents($seedPath);
            if ($seed === false) {
                throw new RuntimeException('No se pudieron leer los datos iniciales de demostración.');
            }

            $pdo->beginTransaction();
            try {
                $pdo->exec($seed);
                $pdo->exec('PRAGMA user_version = 1');
                $pdo->commit();
            } catch (Throwable $e) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                throw $e;
            }
        }

        self::$connection = $pdo;
        return self::$connection;
    }
}
