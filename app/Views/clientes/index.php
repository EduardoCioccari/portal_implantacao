<?php require __DIR__ . '/../layouts/cabecalho.php'; ?>

<?php
/** @var string $sucesso */
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Meus clientes</h1>
    <a href="<?= BASE_URL ?>/clientes/novo" class="btn btn-primary">Novo cliente</a>
</div>

<?php if ($sucesso): ?>
    <div class="alert alert-success"><?= $sucesso ?></div>
<?php endif; ?>

<?php if (empty($clientes)): ?>
    <div class="alert alert-info">Você ainda não tem clientes cadastrados.</div>
<?php else: ?>
    <table class="table table-striped bg-white">
        <thead>
            <tr>
                <th>Nome</th>
                <th>E-mail</th>
                <th>Cadastrado em</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($clientes as $cliente): ?>
                <tr>
                    <td><?= htmlspecialchars($cliente['nome']) ?></td>
                    <td><?= htmlspecialchars($cliente['email']) ?></td>
                    <td><?= date('d/m/Y', strtotime($cliente['data_cadastro'])) ?></td>
                    <td class="text-end">
                        <a href="<?= BASE_URL ?>/itens?cliente=<?= $cliente['id'] ?>" class="btn btn-sm btn-outline-primary">Itens</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php require __DIR__ . '/../layouts/rodape.php'; ?>