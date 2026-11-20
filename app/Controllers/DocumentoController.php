<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Models\Documento;
use App\Models\Usuario;

class DocumentoController
{
    private const PASTA = __DIR__ . '/../../storage/pdfs/';

    // Lista fixa: o ?tipo= da URL só pode ser um destes (cada um aponta para uma coluna)
    private const TIPOS = [
        'consolidado' => 'pdf_consolidado_path',
        'assinado'    => 'pdf_assinado_path',
    ];

    // UC11: cliente envia o termo assinado
    public function enviarAssinado()
    {
        Auth::exigirPerfil('cliente');

        $clienteId = $_SESSION['usuario']['id'];

        // Só pode enviar se o termo já foi gerado
        if (!Documento::buscarDoCliente($clienteId)) {
            http_response_code(404);
            echo 'O termo de conclusão ainda não foi gerado.';
            exit;
        }

        $arquivo = $_FILES['termo'] ?? null;
        $extensao = strtolower(pathinfo($arquivo['name'] ?? '', PATHINFO_EXTENSION));

        $erro = null;

        if (!$arquivo || $arquivo['error'] !== UPLOAD_ERR_OK) {
            $erro = 'Selecione o arquivo do termo assinado.';
        } elseif ($extensao !== 'pdf') {
            $erro = 'O termo assinado deve ser um arquivo PDF.';
        } elseif ($arquivo['size'] > 5 * 1024 * 1024) {
            $erro = 'O arquivo deve ter até 5 MB.';
        }

        // O erro vai pela sessão porque a tela do cliente precisa recarregar os itens
        if ($erro) {
            $_SESSION['erro'] = $erro;
            header('Location: ' . BASE_URL . '/cliente');
            exit;
        }

        // Nome fixo por cliente: um novo envio substitui o anterior
        $nomeArquivo = 'assinado_cliente_' . $clienteId . '.pdf';
        move_uploaded_file($arquivo['tmp_name'], self::PASTA . $nomeArquivo);

        Documento::salvarAssinado($clienteId, $nomeArquivo);

        $_SESSION['sucesso'] = 'Termo assinado enviado com sucesso.';
        header('Location: ' . BASE_URL . '/cliente');
        exit;
    }

    // Cliente baixa o próprio termo (id vem da sessão, nunca da URL)
    public function baixarCliente()
    {
        Auth::exigirPerfil('cliente');

        $this->enviarArquivo($_SESSION['usuario']['id']);
    }

    // UC12: analista baixa o termo de um cliente dele
    public function baixarAnalista()
    {
        Auth::exigirPerfil('analista');

        $clienteId = (int) ($_GET['cliente'] ?? 0);

        if (!Usuario::buscarClienteDoAnalista($clienteId, $_SESSION['usuario']['id'])) {
            http_response_code(404);
            echo 'Cliente não encontrado';
            exit;
        }

        $this->enviarArquivo($clienteId);
    }

    // Confere o tipo pedido e entrega o PDF ao navegador
    private function enviarArquivo(int $clienteId): void
    {
        $tipo = $_GET['tipo'] ?? '';
        $coluna = self::TIPOS[$tipo] ?? null;
        $documento = Documento::buscarDoCliente($clienteId);

        if (!$coluna || !$documento || !$documento[$coluna] || !is_file(self::PASTA . $documento[$coluna])) {
            http_response_code(404);
            echo 'Documento não encontrado';
            exit;
        }

        $caminho = self::PASTA . $documento[$coluna];

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="termo_' . $tipo . '.pdf"');
        header('Content-Length: ' . filesize($caminho));
        readfile($caminho);
        exit;
    }
}
