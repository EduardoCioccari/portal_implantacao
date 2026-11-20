<?php

namespace App\Models;

use App\Core\Database;

class Documento
{
    public static function salvarConsolidado(int $clienteId, string $nomeArquivo): void
    {
        $stmt = Database::conexao()->prepare(
            "INSERT INTO implantacao_documentos (cliente_id, pdf_consolidado_path)
             VALUES (?, ?)
             ON DUPLICATE KEY UPDATE
                pdf_consolidado_path = VALUES(pdf_consolidado_path),
                data_geracao = NOW(),
                pdf_assinado_path = NULL,
                data_upload = NULL"
        );
        $stmt->execute([$clienteId, $nomeArquivo]);
    }

    // Retorna o registro de documentos do cliente (ou false se o PDF ainda não foi gerado)
    public static function buscarDoCliente(int $clienteId): array|false
    {
        $stmt = Database::conexao()->prepare(
            "SELECT pdf_consolidado_path, pdf_assinado_path, data_geracao, data_upload
             FROM implantacao_documentos
             WHERE cliente_id = ?"
        );
        $stmt->execute([$clienteId]);

        return $stmt->fetch();
    }

    public static function salvarAssinado(int $clienteId, string $nomeArquivo): void
    {
        $stmt = Database::conexao()->prepare(
            "UPDATE implantacao_documentos
             SET pdf_assinado_path = ?, data_upload = NOW()
             WHERE cliente_id = ?"
        );
        $stmt->execute([$nomeArquivo, $clienteId]);
    }
}
