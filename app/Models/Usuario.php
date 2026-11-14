<?php

namespace App\Models;

use App\Core\Database;

class Usuario
{
    public static function buscarPorEmail(string $email): array|false
    {
        $stmt = Database::conexao()->prepare('SELECT * FROM usuarios WHERE email = ?');
        $stmt->execute([$email]);

        return $stmt->fetch();
    }

    public static function clientesDoAnalista(int $analistaId): array
{
    $stmt = Database::conexao()->prepare(
        "SELECT id, nome, email, data_cadastro
         FROM usuarios
         WHERE tipo = 'cliente' AND analista_id = ?
         ORDER BY nome"
    );
    $stmt->execute([$analistaId]);

    return $stmt->fetchAll();
}

}