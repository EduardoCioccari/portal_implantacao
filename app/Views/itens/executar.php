<?php require __DIR__ . '/../layouts/cabecalho.php'; ?>

<?php
/** @var array $item */
/** @var array|null $erros */
?>

<a href="<?= BASE_URL ?>/cliente" class="btn btn-link px-0 mb-2">&larr; Minha implantação</a>
<h1 class="h3 mb-3">Marcar como executado</h1>

<div class="card mb-3">
    <div class="card-body">
        <h2 class="h5"><?= htmlspecialchars($item['titulo']) ?></h2>
        <p class="mb-1"><?= htmlspecialchars($item['descricao']) ?></p>
        <p class="small text-muted mb-0">Prazo: <?= date('d/m/Y', strtotime($item['prazo_previsto'])) ?></p>
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
                <label for="observacao" class="form-label">Como foi a realização desta tarefa?</label>
                <textarea class="form-control" id="observacao" name="observacao" rows="4" required><?= htmlspecialchars($observacao ?? '') ?></textarea>
            </div>

            <button type="submit" class="btn btn-success">Enviar para validação</button>
            <a href="<?= BASE_URL ?>/cliente" class="btn btn-outline-secondary">Cancelar</a>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layouts/rodape.php'; ?>
