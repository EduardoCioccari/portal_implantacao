<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Models\Usuario;

class ClienteController
{
    public function index()
    {
        Auth::exigirPerfil('analista');

        $clientes = Usuario::clientesDoAnalista($_SESSION['usuario']['id']);

        $sucesso = $_SESSION['sucesso'] ?? null;
        unset($_SESSION['sucesso']);


        require __DIR__ . '/../Views/clientes/index.php';
    }

    public function novo()
    {
        Auth::exigirPerfil('analista');

        require __DIR__ . '/../Views/clientes/form.php';
    }

    public function salvar()
    {
        Auth::exigirPerfil('analista');

        $dados = [
            'nome'  => trim($_POST['nome'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
        ];
        $senha = $_POST['senha'] ?? '';

        $erros = [];

        if ($dados['nome'] === '' || mb_strlen($dados['nome']) > 150) {
            $erros[] = 'Informe o nome (até 150 caracteres).';
        }

        if (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL) || mb_strlen($dados['email']) > 150) {
            $erros[] = 'Informe um e-mail válido.';
        } elseif (Usuario::buscarPorEmail($dados['email'])) {
            $erros[] = 'Já existe um usuário com este e-mail.';
        }

        if (strlen($senha) < 8) {
            $erros[] = 'A senha deve ter no mínimo 8 caracteres.';
        }

        if ($erros) {
            require __DIR__ . '/../Views/clientes/form.php';
            return;
        }

        Usuario::criarCliente(
            $dados['nome'],
            $dados['email'],
            password_hash($senha, PASSWORD_DEFAULT),
            $_SESSION['usuario']['id']
        );

        $_SESSION['sucesso'] = 'Cliente cadastrado com sucesso.';
        header('Location: ' . BASE_URL . '/analista');
        exit;
    }
}
