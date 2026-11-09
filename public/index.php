<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Controllers\LoginController;

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$base = dirname($_SERVER['SCRIPT_NAME']);

$rota = '/' . trim(substr($uri, strlen($base)), '/');

$metodo = $_SERVER['REQUEST_METHOD'];

switch ("$metodo $rota") {
    case 'GET /':
        echo 'Página inicial';
        break;

    case 'GET /login':
        $controller = new LoginController();
        $controller->formulario();
        break;

    case 'POST /login':
        $controller = new LoginController();
        $controller->entrar();
        break;


    default:
        http_response_code(404);
        echo 'Página não encontrada';
}
