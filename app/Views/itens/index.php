<?php require __DIR__ . '/../layouts/cabecalho.php'; ?>

<?php
/** @var array $cliente */
/** @var string|null $sucesso */
/** @var array|false $documento */

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

<?php if ($documento): ?>
    <div class="card mb-3 border-success">
        <div class="card-body">
            <h2 class="h5">Termo de conclusão</h2>
            <p class="small text-muted">Gerado em <?= date('d/m/Y H:i', strtotime($documento['data_geracao'])) ?></p>

            <a href="<?= BASE_URL ?>/clientes/termo?cliente=<?= $cliente['id'] ?>&tipo=consolidado" class="btn btn-outline-primary">Baixar termo</a>

            <?php if ($documento['pdf_assinado_path']): ?>
                <a href="<?= BASE_URL ?>/clientes/termo?cliente=<?= $cliente['id'] ?>&tipo=assinado" class="btn btn-success">Baixar termo assinado</a>
                <span class="small text-muted ms-2">Enviado pelo cliente em <?= date('d/m/Y H:i', strtotime($documento['data_upload'])) ?></span>
            <?php else: ?>
                <span class="small text-muted ms-2">Aguardando o cliente enviar o termo assinado.</span>
            <?php endif; ?>
        </div>
    </div>
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
