<?php require __DIR__ . '/../layouts/cabecalho.php'; ?>

<?php
/** @var array $item */
/** @var array|null $erros */
?>

<a href="<?= BASE_URL ?>/validacoes" class="btn btn-link px-0 mb-2">&larr; Itens aguardando validação</a>
<h1 class="h3 mb-3">Avaliar item – <?= htmlspecialchars($item['cliente_nome']) ?></h1>

<div class="card mb-3">
    <div class="card-body">
        <h2 class="h5"><?= htmlspecialchars($item['titulo']) ?></h2>
        <p class="mb-1"><?= htmlspecialchars($item['descricao']) ?></p>
        <p class="small text-muted mb-0">Prazo: <?= date('d/m/Y', strtotime($item['prazo_previsto'])) ?></p>
    </div>
</div>

<div class="card mb-3 border-warning">
    <div class="card-body">
        <h2 class="h6">Observação do cliente
            <span class="text-muted fw-normal">(<?= date('d/m/Y H:i', strtotime($item['data_registro'])) ?>)</span>
        </h2>
        <p class="mb-0"><?= nl2br(htmlspecialchars($item['observacao_cliente'])) ?></p>
    </div>
</div>

<?php if (!empty($erros)): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($erros as $erro): ?>
                <li><?= $erro ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form method="post" action="">
            <div class="mb-3">
                <label for="observacao" class="form-label">Sua observação</label>
                <textarea class="form-control" id="observacao" name="observacao" rows="4" required><?= htmlspecialchars($observacao ?? '') ?></textarea>
                <div class="form-text">Ao devolver, explique ao cliente o que precisa ser ajustado.</div>
            </div>

            <!-- Os dois botões enviam o mesmo formulário -->
            <button type="submit" name="acao" value="validar" class="btn btn-success">Validar (concluir)</button>
            <button type="submit" name="acao" value="devolver" class="btn btn-outline-danger">Devolver ao cliente</button>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layouts/rodape.php'; ?>
