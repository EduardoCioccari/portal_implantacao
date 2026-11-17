<?php

namespace App\Models;

use App\Core\Database;

class Item
{
    public static function listarPorCliente(int $clienteId): array
    {
        $stmt = Database::conexao()->prepare(
            "SELECT id, titulo, descricao, prazo_previsto, status
             FROM itens
             WHERE cliente_id = ?
             ORDER BY prazo_previsto, id"
        );
        $stmt->execute([$clienteId]);

        return $stmt->fetchAll();
    }

    public static function criar(int $clienteId, string $titulo, string $descricao, string $prazo): void
    {
        $stmt = Database::conexao()->prepare(
            "INSERT INTO itens (cliente_id, titulo, descricao, prazo_previsto)
             VALUES (?, ?, ?, ?)"
        );
        $stmt->execute([$clienteId, $titulo, $descricao, $prazo]);
    }

    // Só encontra o item se ele pertencer a este cliente (proteção contra IDOR)
    public static function buscarDoCliente(int $itemId, int $clienteId): array|false
    {
        $stmt = Database::conexao()->prepare(
            "SELECT id, titulo, descricao, prazo_previsto, status
             FROM itens
             WHERE id = ? AND cliente_id = ?"
        );
        $stmt->execute([$itemId, $clienteId]);

        return $stmt->fetch();
    }
}
