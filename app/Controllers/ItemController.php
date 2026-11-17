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

    // Cliente ve os itens da própria implantação
    public function meusItens()
    {
        Auth::exigirPerfil('cliente');

        $itens = Item::listarPorCliente($_SESSION['usuario']['id']);

        $sucesso = $_SESSION['sucesso'] ?? null;
        unset($_SESSION['sucesso']);

        require __DIR__ . '/../Views/itens/meus.php';
    }

    // UC06 (GET): formulário para o cliente descrever como foi a tarefa
    public function formExecutar()
    {
        Auth::exigirPerfil('cliente');

        $item = $this->itemPendenteDoCliente();

        require __DIR__ . '/../Views/itens/executar.php';
    }

    // UC06 (POST): grava a observação e manda o item para validação
    public function executar()
    {
        Auth::exigirPerfil('cliente');

        $item = $this->itemPendenteDoCliente();
        $observacao = trim($_POST['observacao'] ?? '');

        if ($observacao === '') {
            $erros = ['Descreva como foi a realização da tarefa.'];
            require __DIR__ . '/../Views/itens/executar.php';
            return;
        }

        Item::marcarExecutado($item['id'], $_SESSION['usuario']['id'], $observacao);

        $_SESSION['sucesso'] = 'Item enviado para validação do analista.';
        header('Location: ' . BASE_URL . '/cliente');
        exit;
    }

    // Lê ?id= da URL e confere se o item é deste cliente e ainda está pendente
    private function itemPendenteDoCliente(): array
    {
        $itemId = (int) ($_GET['id'] ?? 0);
        $item = Item::buscarDoCliente($itemId, $_SESSION['usuario']['id']);

        if (!$item) {
            http_response_code(404);
            echo 'Item não encontrado';
            exit;
        }

        if ($item['status'] !== 'pendente') {
            http_response_code(409);
            echo 'Este item não está pendente.';
            exit;
        }

        return $item;
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
