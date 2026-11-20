<?php require __DIR__ . '/../layouts/cabecalho.php'; ?>

<?php
/** @var array $itens */
/** @var string|null $sucesso */
/** @var string|null $erro */
/** @var array|false $documento */

$status = require __DIR__ . '/status.php';
?>

<h1 class="h3 mb-3">Minha implantação</h1>

<?php if ($sucesso): ?>
    <div class="alert alert-success"><?= $sucesso ?></div>
<?php endif; ?>

<?php if ($erro): ?>
    <div class="alert alert-danger"><?= $erro ?></div>
<?php endif; ?>

<?php if ($documento): ?>
    <div class="card mb-3 border-success">
        <div class="card-body">
            <h2 class="h5">Termo de conclusão</h2>
            <p>Todos os itens foram concluídos. Baixe o termo, assine e envie de volta.</p>

            <a href="<?= BASE_URL ?>/termo?tipo=consolidado" class="btn btn-outline-primary mb-3">Baixar termo para assinar</a>

            <?php if ($documento['pdf_assinado_path']): ?>
                <div class="alert alert-success py-2">
                    Termo assinado enviado em <?= date('d/m/Y H:i', strtotime($documento['data_upload'])) ?>.
                    <a href="<?= BASE_URL ?>/termo?tipo=assinado" class="alert-link">Ver arquivo enviado</a>
                </div>
            <?php endif; ?>

            <form method="post" action="<?= BASE_URL ?>/termo/enviar" enctype="multipart/form-data">
                <label for="termo" class="form-label">
                    <?= $documento['pdf_assinado_path'] ? 'Enviar novamente (substitui o anterior)' : 'Enviar termo assinado' ?> (PDF, até 5 MB)
                </label>
                <div class="input-group">
                    <input type="file" class="form-control" id="termo" name="termo" accept="application/pdf" required>
                    <button type="submit" class="btn btn-success">Enviar</button>
                </div>
            </form>
        </div>
    </div>
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
                        <?php if ($item['status'] === 'pendente' && $item['ultima_obs_tipo'] === 'devolucao'): ?>
                            <div class="alert alert-danger small py-1 px-2 mt-2 mb-0">
                                <strong>Devolvido pelo analista:</strong>
                                <?= nl2br(htmlspecialchars($item['ultima_obs_texto'])) ?>
                            </div>
                        <?php endif; ?>
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
