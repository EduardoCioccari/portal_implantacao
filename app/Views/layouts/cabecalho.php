<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portal de Implantação</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-primary mb-4">
        <div class="container">
            <span class="navbar-brand">Portal de Implantação</span>

            <!-- Menu muda conforme o perfil do usuário logado -->
            <div class="me-auto">
                <?php if ($_SESSION['usuario']['tipo'] === 'analista'): ?>
                    <a href="<?= BASE_URL ?>/analista" class="btn btn-sm btn-primary">Meus clientes</a>
                    <a href="<?= BASE_URL ?>/validacoes" class="btn btn-sm btn-primary">Validações</a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/cliente" class="btn btn-sm btn-primary">Minha implantação</a>
                <?php endif; ?>
            </div>

            <div class="text-white">
                <?= htmlspecialchars($_SESSION['usuario']['nome']) ?>
                <a href="<?= BASE_URL ?>/logout" class="btn btn-sm btn-outline-light ms-2">Sair</a>
            </div>
        </div>
    </nav>
    <main class="container">
