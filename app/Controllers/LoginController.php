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
        $usuario = Usuario::buscarPorEmail($email);

        var_dump($usuario);

    }

}
