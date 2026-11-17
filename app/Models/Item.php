<?php

namespace App\Models;

use App\Core\Database;

class Item
{
    public static function listarPorCliente(int $clienteId): array
    {
        // O LEFT JOIN traz junto a observação mais recente de cada item (se houver),
        // para mostrar, por exemplo, o motivo de uma devolução
        $stmt = Database::conexao()->prepare(
            "SELECT i.id, i.titulo, i.descricao, i.prazo_previsto, i.status,
                    o.tipo AS ultima_obs_tipo, o.texto AS ultima_obs_texto
             FROM itens i
             LEFT JOIN item_observacoes o
                    ON o.id = (SELECT MAX(id) FROM item_observacoes WHERE item_id = i.id)
             WHERE i.cliente_id = ?
             ORDER BY i.prazo_previsto, i.id"
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

    public static function marcarExecutado(int $itemId, int $clienteId, string $observacao): void
    {
        $pdo = Database::conexao();

        $pdo->beginTransaction();

        try {
            $stmt = $pdo->prepare(
                "UPDATE itens SET status = 'aguardando_validacao'
                 WHERE id = ? AND cliente_id = ? AND status = 'pendente'"
            );
            $stmt->execute([$itemId, $clienteId]);

            $stmt = $pdo->prepare(
                "INSERT INTO item_observacoes (item_id, usuario_id, tipo, texto)
                 VALUES (?, ?, 'execucao', ?)"
            );
            $stmt->execute([$itemId, $clienteId, $observacao]);

            $pdo->commit();
        } catch (\Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }
    }
}
