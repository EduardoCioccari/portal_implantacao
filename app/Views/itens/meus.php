<?php require __DIR__ . '/../layouts/cabecalho.php'; ?>

<?php
/** @var array $itens */
/** @var string|null $sucesso */

$status = require __DIR__ . '/status.php';
?>

<h1 class="h3 mb-3">Minha implantação</h1>

<?php if ($sucesso): ?>
    <div class="alert alert-success"><?= $sucesso ?></div>
<?php endif; ?>

<?php if (empty($itens)): ?>
    <div class="alert alert-info">Nenhum item foi cadastrado para a sua implantação ainda.</div>
<?php else: ?>
    <table class="table bg-white">
        <thead>
            <tr>
                <th>Item</th>
                <th>Prazo</th>
                <th>Status</th>
                <th></th>
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
                    <td class="text-end">
                        <?php if ($item['status'] === 'pendente'): ?>
                            <a href="<?= BASE_URL ?>/itens/executar?id=<?= $item['id'] ?>" class="btn btn-sm btn-success">Marcar como executado</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php require __DIR__ . '/../layouts/rodape.php'; ?>
