<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Core\Auth;
use App\Controllers\LoginController;
use App\Controllers\ClienteController;
use App\Controllers\ItemController;

session_start();


$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$base = dirname($_SERVER['SCRIPT_NAME']);
define('BASE_URL', $base);

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

    case 'GET /analista':
        $controller = new ClienteController();
        $controller->index();
        break;

    case 'GET /cliente':
        Auth::exigirPerfil('cliente');
        echo 'Área do cliente. Olá, ' . $_SESSION['usuario']['nome'];
        break;

    case 'GET /logout':
        $controller = new LoginController();
        $controller->sair();
        break;

    case 'GET /clientes/novo':
        $controller = new ClienteController();
        $controller->novo();
        break;

    case 'POST /clientes/novo':
        $controller = new ClienteController();
        $controller->salvar();
        break;

    case 'GET /itens':
        $controller = new ItemController();
        $controller->index();
        break;

    case 'GET /itens/novo':
        $controller = new ItemController();
        $controller->novo();
        break;

    case 'POST /itens/novo':
        $controller = new ItemController();
        $controller->salvar();
        break;

    default:
        http_response_code(404);
        echo 'Página não encontrada';
}
