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

        require __DIR__ . '/../Views/clientes/index.php';
    }
}
