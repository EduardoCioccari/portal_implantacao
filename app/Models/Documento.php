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
}
