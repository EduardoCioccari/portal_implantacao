<?php

namespace App\Core;

class Auth
{
    public static function exigirPerfil(string $tipo): void
    {
        if (!isset($_SESSION['usuario'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        if ($_SESSION['usuario']['tipo'] !== $tipo) {
            http_response_code(403);
            echo 'Acesso negado';
            exit;
        }
    }
}
