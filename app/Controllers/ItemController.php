<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Models\Item;
use App\Models\Usuario;

class ItemController
{
    public function index()
    {
        Auth::exigirPerfil('analista');

        $cliente = $this->clienteDoAnalista();
        $itens = Item::listarPorCliente($cliente['id']);

        $sucesso = $_SESSION['sucesso'] ?? null;
        unset($_SESSION['sucesso']);

        require __DIR__ . '/../Views/itens/index.php';
    }

    public function novo()
    {
        Auth::exigirPerfil('analista');

        $cliente = $this->clienteDoAnalista();

        require __DIR__ . '/../Views/itens/form.php';
    }

    public function salvar()
    {
        Auth::exigirPerfil('analista');

        $cliente = $this->clienteDoAnalista();

        $dados = [
            'titulo'    => trim($_POST['titulo'] ?? ''),
            'descricao' => trim($_POST['descricao'] ?? ''),
            'prazo'     => $_POST['prazo'] ?? '',
        ];

        $erros = [];

        if ($dados['titulo'] === '' || mb_strlen($dados['titulo']) > 150) {
            $erros[] = 'Informe o título (até 150 caracteres).';
        }

        if ($dados['descricao'] === '') {
            $erros[] = 'Informe a descrição.';
        }

        $data = \DateTime::createFromFormat('Y-m-d', $dados['prazo']);
        if (!$data || $data->format('Y-m-d') !== $dados['prazo']) {
            $erros[] = 'Informe um prazo válido.';
        }

        if ($erros) {
            require __DIR__ . '/../Views/itens/form.php';
            return;
        }

        Item::criar($cliente['id'], $dados['titulo'], $dados['descricao'], $dados['prazo']);

        $_SESSION['sucesso'] = 'Item cadastrado com sucesso.';
        header('Location: ' . BASE_URL . '/itens?cliente=' . $cliente['id']);
        exit;
    }

    // Validação que só permite o analista acessar clientes que são dele
    private function clienteDoAnalista(): array
    {
        $clienteId = (int) ($_GET['cliente'] ?? 0);
        $cliente = Usuario::buscarClienteDoAnalista($clienteId, $_SESSION['usuario']['id']);

        if (!$cliente) {
            http_response_code(404);
            echo 'Cliente não encontrado';
            exit;
        }

        return $cliente;
    }
}
