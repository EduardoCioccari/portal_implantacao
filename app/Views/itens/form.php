<?php require __DIR__ . '/../layouts/cabecalho.php'; ?>

<?php
/** @var array $cliente */
/** @var array|null $erros */
?>

<h1 class="h3 mb-3">Novo item – <?= htmlspecialchars($cliente['nome']) ?></h1>

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
                <label for="titulo" class="form-label">Título</label>
                <input type="text" class="form-control" id="titulo" name="titulo" maxlength="150" value="<?= htmlspecialchars($dados['titulo'] ?? '') ?>" required>
            </div>
            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição</label>
                <textarea class="form-control" id="descricao" name="descricao" rows="4" required><?= htmlspecialchars($dados['descricao'] ?? '') ?></textarea>
            </div>
            <div class="mb-3">
                <label for="prazo" class="form-label">Prazo previsto</label>
                <input type="date" class="form-control" id="prazo" name="prazo" value="<?= htmlspecialchars($dados['prazo'] ?? '') ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Salvar</button>
            <a href="<?= BASE_URL ?>/itens?cliente=<?= $cliente['id'] ?>" class="btn btn-outline-secondary">Cancelar</a>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layouts/rodape.php'; ?>
