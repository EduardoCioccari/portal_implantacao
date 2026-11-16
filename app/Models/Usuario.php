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

    public static function criarCliente(string $nome, string $email, string $senhaHash, int $analistaId): void
    {
        $stmt = Database::conexao()->prepare(
            "INSERT INTO usuarios (nome, email, senha_hash, tipo, analista_id)
             VALUES (?, ?, ?, 'cliente', ?)"
        );
        $stmt->execute([$nome, $email, $senhaHash, $analistaId]);
    }

    // Só encontra o cliente se ele pertencer ao analista
    public static function buscarClienteDoAnalista(int $clienteId, int $analistaId): array|false
    {
        $stmt = Database::conexao()->prepare(
            "SELECT id, nome, email
             FROM usuarios
             WHERE id = ? AND tipo = 'cliente' AND analista_id = ?"
        );
        $stmt->execute([$clienteId, $analistaId]);

        return $stmt->fetch();
    }
}