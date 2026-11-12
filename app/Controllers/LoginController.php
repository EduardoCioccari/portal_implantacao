<?php

namespace App\Controllers;

use App\Models\Usuario;

class LoginController
{
    public function formulario()
    {
        require __DIR__ . '/../Views/login/formulario.php';
    }

    public function entrar()
    {
        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';

        $usuario = Usuario::buscarPorEmail($email);

        if (!$usuario || !password_verify($senha, $usuario['senha_hash'])) {
            $erro = 'E-mail ou senha inválidos.';
            require __DIR__ . '/../Views/login/formulario.php';
        return;
    }

    session_regenerate_id(true);

    $_SESSION['usuario'] = [
        'id'   => $usuario['id'],
        'nome' => $usuario['nome'],
        'tipo' => $usuario['tipo'],
    ];

    header('Location: ' . BASE_URL . '/' . $usuario['tipo']);
    exit;

    }

    public function sair()
    {
        $_SESSION = [];
        session_destroy();

        header('Location: ' . BASE_URL . '/login');
        exit;
    }

}
