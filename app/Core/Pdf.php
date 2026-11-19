<?php

namespace App\Core;

use App\Models\Documento;
use App\Models\Item;
use App\Models\Usuario;
use Dompdf\Dompdf;

class Pdf
{
    private const PASTA_PDFS = __DIR__ . '/../../storage/pdfs/';
    private const PASTA_ASSINATURAS = __DIR__ . '/../../storage/assinaturas/';

    public static function gerarConclusao(int $clienteId, array $analista): void
    {
        $cliente = Usuario::buscarClienteDoAnalista($clienteId, $analista['id']);
        $itens = Item::concluidosDoCliente($clienteId);

        $assinaturaSrc = null;
        $assinatura = Usuario::buscarAssinatura($analista['id']);
        if ($assinatura && is_file(self::PASTA_ASSINATURAS . $assinatura)) {
            $extensao = pathinfo($assinatura, PATHINFO_EXTENSION);
            $mime = $extensao === 'png' ? 'image/png' : 'image/jpeg';
            $assinaturaSrc = "data:$mime;base64," . base64_encode(file_get_contents(self::PASTA_ASSINATURAS . $assinatura));
        }

        ob_start();
        require __DIR__ . '/../Views/pdf/conclusao.php';
        $html = ob_get_clean();

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        if (!is_dir(self::PASTA_PDFS)) {
            mkdir(self::PASTA_PDFS, 0755, true);
        }

        $nomeArquivo = 'conclusao_cliente_' . $clienteId . '.pdf';
        file_put_contents(self::PASTA_PDFS . $nomeArquivo, $dompdf->output());

        Documento::salvarConsolidado($clienteId, $nomeArquivo);
    }
}
