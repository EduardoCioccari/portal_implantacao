<?php

namespace App\Models;

use App\Core\Database;

class Item
{
    public static function listarPorCliente(int $clienteId): array
    {
        
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

    // Só encontra o item se ele pertencer a este cliente
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

    // Itens aguardando validação de todos os clientes do analista
    public static function aguardandoDoAnalista(int $analistaId): array
    {
        $stmt = Database::conexao()->prepare(
            "SELECT i.id, i.titulo, i.prazo_previsto,
                    u.nome AS cliente_nome,
                    o.texto AS observacao_cliente, o.data_registro
             FROM itens i
             JOIN usuarios u ON u.id = i.cliente_id
             JOIN item_observacoes o
               ON o.id = (SELECT MAX(id) FROM item_observacoes
                          WHERE item_id = i.id AND tipo = 'execucao')
             WHERE i.status = 'aguardando_validacao' AND u.analista_id = ?
             ORDER BY o.data_registro"
        );
        $stmt->execute([$analistaId]);

        return $stmt->fetchAll();
    }

    // Só encontra o item se o cliente pertence ao  analista
    public static function buscarDoAnalista(int $itemId, int $analistaId): array|false
    {
        $stmt = Database::conexao()->prepare(
            "SELECT i.id, i.titulo, i.descricao, i.prazo_previsto, i.status,
                    u.id AS cliente_id, u.nome AS cliente_nome,
                    o.texto AS observacao_cliente, o.data_registro
             FROM itens i
             JOIN usuarios u ON u.id = i.cliente_id
             LEFT JOIN item_observacoes o
                    ON o.id = (SELECT MAX(id) FROM item_observacoes
                               WHERE item_id = i.id AND tipo = 'execucao')
             WHERE i.id = ? AND u.analista_id = ?"
        );
        $stmt->execute([$itemId, $analistaId]);

        return $stmt->fetch();
    }

    // Verdadeiro se o cliente tem itens e todos estão concluídos
    public static function todosConcluidos(int $clienteId): bool
    {
        $stmt = Database::conexao()->prepare(
            "SELECT COUNT(*) AS total, SUM(status = 'concluido') AS concluidos
             FROM itens
             WHERE cliente_id = ?"
        );
        $stmt->execute([$clienteId]);
        $resultado = $stmt->fetch();

        return $resultado['total'] > 0 && $resultado['total'] == $resultado['concluidos'];
    }

    // Itens do cliente com a data em que o analista validou cada um (para o PDF)
    public static function concluidosDoCliente(int $clienteId): array
    {
        $stmt = Database::conexao()->prepare(
            "SELECT i.titulo, i.prazo_previsto, MAX(o.data_registro) AS data_conclusao
             FROM itens i
             LEFT JOIN item_observacoes o ON o.item_id = i.id AND o.tipo = 'validacao'
             WHERE i.cliente_id = ?
             GROUP BY i.id
             ORDER BY i.prazo_previsto, i.id"
        );
        $stmt->execute([$clienteId]);

        return $stmt->fetchAll();
    }

    // Mesma transação do marcarExecutado, mas feita pelo analista
    public static function avaliar(int $itemId, int $analistaId, string $novoStatus, string $tipoObservacao, string $texto): void
    {
        $pdo = Database::conexao();

        $pdo->beginTransaction();

        try {
            // Fazendo JOIN com usuarios para garantir que o item é de um cliente do analista
            $stmt = $pdo->prepare(
                "UPDATE itens i
                 JOIN usuarios u ON u.id = i.cliente_id
                 SET i.status = ?
                 WHERE i.id = ? AND u.analista_id = ? AND i.status = 'aguardando_validacao'"
            );
            $stmt->execute([$novoStatus, $itemId, $analistaId]);

            $stmt = $pdo->prepare(
                "INSERT INTO item_observacoes (item_id, usuario_id, tipo, texto)
                 VALUES (?, ?, ?, ?)"
            );
            $stmt->execute([$itemId, $analistaId, $tipoObservacao, $texto]);

            $pdo->commit();
        } catch (\Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }
    }
}
