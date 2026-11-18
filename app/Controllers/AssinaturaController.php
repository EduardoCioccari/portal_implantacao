<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Models\Usuario;

class AssinaturaController
{
    // A imagem não pode ser aberta pela URL
    private const PASTA = __DIR__ . '/../../storage/assinaturas/';

    public function formulario()
    {
        Auth::exigirPerfil('analista');

        $assinatura = Usuario::buscarAssinatura($_SESSION['usuario']['id']);

        $sucesso = $_SESSION['sucesso'] ?? null;
        unset($_SESSION['sucesso']);

        require __DIR__ . '/../Views/assinatura/form.php';
    }

    public function salvar()
    {
        Auth::exigirPerfil('analista');

        $analistaId = $_SESSION['usuario']['id'];
        $assinatura = Usuario::buscarAssinatura($analistaId);

        $arquivo = $_FILES['assinatura'] ?? null;
        $extensao = strtolower(pathinfo($arquivo['name'] ?? '', PATHINFO_EXTENSION));

        $erros = [];

        if (!$arquivo || $arquivo['error'] !== UPLOAD_ERR_OK) {
            $erros[] = 'Selecione uma imagem.';
        } elseif (!in_array($extensao, ['png', 'jpg', 'jpeg'])) {
            $erros[] = 'A imagem deve ser PNG ou JPG.';
        } elseif ($arquivo['size'] > 2 * 1024 * 1024) {
            $erros[] = 'A imagem deve ter até 2 MB.';
        }

        if ($erros) {
            require __DIR__ . '/../Views/assinatura/form.php';
            return;
        }

        // Cria a pasta na primeira vez
        if (!is_dir(self::PASTA)) {
            mkdir(self::PASTA, 0755, true);
        }

        // Gerando nome
        $nomeArquivo = 'analista_' . $analistaId . '_' . time() . '.' . $extensao;

        // Tira o arquivo da pasta temporária do PHP e guarda no storage
        move_uploaded_file($arquivo['tmp_name'], self::PASTA . $nomeArquivo);

        // Apaga a assinatura antiga para não acumular arquivos
        if ($assinatura && is_file(self::PASTA . $assinatura)) {
            unlink(self::PASTA . $assinatura);
        }

        Usuario::salvarAssinatura($analistaId, $nomeArquivo);

        $_SESSION['sucesso'] = 'Assinatura salva com sucesso.';
        header('Location: ' . BASE_URL . '/assinatura');
        exit;
    }
}
