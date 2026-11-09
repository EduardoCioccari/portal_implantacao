<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $pdo = null;

    public static function conexao(): PDO
    {
        if (self::$pdo === null) {
            $databaseConfig = require __DIR__ . '/../../config/database.php';

            try {
                self::$pdo = new PDO(
                    "mysql:host={$databaseConfig['host']};dbname={$databaseConfig['dbname']};charset=utf8mb4",
                    $databaseConfig['usuario'],
                    $databaseConfig['senha']
                );
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                self::$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

            } catch (PDOException $e) {
                die("Erro na conexão com o banco de dados: " . $e->getMessage());
            }
        }

        return self::$pdo;
    }
}
