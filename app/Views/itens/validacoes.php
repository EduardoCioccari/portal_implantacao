<?php require __DIR__ . '/../layouts/cabecalho.php'; ?>

<?php
/** @var array $itens */
/** @var string|null $sucesso */
?>

<h1 class="h3 mb-3">Itens aguardando validação</h1>

<?php if ($sucesso): ?>
    <div class="alert alert-success"><?= $sucesso ?></div>
<?php endif; ?>

<?php if (empty($itens)): ?>
    <div class="alert alert-info">Nenhum item aguardando validação no momento.</div>
<?php else: ?>
    <table class="table bg-white">
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Item</th>
                <th>Observação do cliente</th>
                <th>Enviado em</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($itens as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['cliente_nome']) ?></td>
                    <td><?= htmlspecialchars($item['titulo']) ?></td>
                    <td class="small"><?= nl2br(htmlspecialchars($item['observacao_cliente'])) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($item['data_registro'])) ?></td>
                    <td class="text-end">
                        <a href="<?= BASE_URL ?>/itens/avaliar?id=<?= $item['id'] ?>" class="btn btn-sm btn-primary">Avaliar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php require __DIR__ . '/../layouts/rodape.php'; ?>
