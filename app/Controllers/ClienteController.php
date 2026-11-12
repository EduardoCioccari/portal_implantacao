<?php

namespace App\Controllers;

use App\Core\Auth;

class ClienteController
{
    public function index()
    {
        Auth::exigirPerfil('analista');

        require __DIR__ . '/../Views/clientes/index.php';
    }
}
