<?php require __DIR__ . '/../layouts/cabecalho.php'; ?>

<?php
/** @var array $cliente */
/** @var string|null $sucesso */

$status = require __DIR__ . '/status.php';
?>

<a href="<?= BASE_URL ?>/analista" class="btn btn-link px-0 mb-2">&larr; Meus clientes</a>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Itens da implantação – <?= htmlspecialchars($cliente['nome']) ?></h1>
    <a href="<?= BASE_URL ?>/itens/novo?cliente=<?= $cliente['id'] ?>" class="btn btn-primary">Novo item</a>
</div>

<?php if ($sucesso): ?>
    <div class="alert alert-success"><?= $sucesso ?></div>
<?php endif; ?>

<?php if (empty($itens)): ?>
    <div class="alert alert-info">Nenhum item cadastrado para este cliente.</div>
<?php else: ?>
    <table class="table bg-white">
        <thead>
            <tr>
                <th>Item</th>
                <th>Prazo</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($itens as $item): ?>
                <?php [$rotulo, $cor] = $status[$item['status']]; ?>
                <tr>
                    <td>
                        <strong><?= htmlspecialchars($item['titulo']) ?></strong>
                        <div class="small text-muted"><?= htmlspecialchars($item['descricao']) ?></div>
                    </td>
                    <td><?= date('d/m/Y', strtotime($item['prazo_previsto'])) ?></td>
                    <td><span class="badge text-bg-<?= $cor ?>"><?= $rotulo ?></span></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php require __DIR__ . '/../layouts/rodape.php'; ?>
