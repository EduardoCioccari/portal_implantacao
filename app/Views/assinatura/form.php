<?php require __DIR__ . '/../layouts/cabecalho.php'; ?>

<?php
/** @var string|null $assinatura */
/** @var string|null $sucesso */
/** @var array|null $erros */
?>

<h1 class="h3 mb-3">Minha assinatura</h1>

<?php if (!empty($sucesso)): ?>
    <div class="alert alert-success"><?= $sucesso ?></div>
<?php endif; ?>

<?php if (!empty($erros)): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($erros as $erro): ?>
                <li><?= $erro ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php if ($assinatura): ?>
    <div class="alert alert-info">Assinatura cadastrada ✓ Ela será usada no PDF de conclusão. Envie outra imagem para substituir.</div>
<?php else: ?>
    <div class="alert alert-warning">Você ainda não cadastrou sua assinatura. Ela é necessária para gerar o PDF de conclusão.</div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form method="post" action="" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="assinatura" class="form-label">Imagem da assinatura (PNG ou JPG, até 2 MB)</label>
                <input type="file" class="form-control" id="assinatura" name="assinatura" accept="image/png,image/jpeg" required>
            </div>

            <button type="submit" class="btn btn-primary">Salvar assinatura</button>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layouts/rodape.php'; ?>
