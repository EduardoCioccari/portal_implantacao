<?php require __DIR__ . '/../layouts/cabecalho.php'; ?>

<h1 class="h3 mb-3">Novo cliente</h1>

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
                <label for="nome" class="form-label">Nome da organização</label>
                <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($dados['nome'] ?? '') ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">E-mail de acesso</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($dados['email'] ?? '') ?>" required>
            </div>
            <div class="mb-3">
                <label for="senha" class="form-label">Senha inicial</label>
                <input type="password" class="form-control" id="senha" name="senha" minlength="8" required>
                <div class="form-text">Mínimo de 8 caracteres. Informe essa senha ao cliente.</div>
            </div>

            <button type="submit" class="btn btn-primary">Salvar</button>
            <a href="<?= BASE_URL ?>/analista" class="btn btn-outline-secondary">Cancelar</a>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layouts/rodape.php'; ?>