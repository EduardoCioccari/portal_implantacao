<?php require __DIR__ . '/../layouts/cabecalho.php'; ?>

<?php if (empty($clientes)): ?>
    <div class="alert alert-info">Você ainda não tem clientes cadastrados.</div>
<?php else: ?>
    <table class="table table-striped bg-white">
        <thead>
            <tr>
                <th>Nome</th>
                <th>E-mail</th>
                <th>Cadastrado em</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($clientes as $cliente): ?>
                <tr>
                    <td><?= htmlspecialchars($cliente['nome']) ?></td>
                    <td><?= htmlspecialchars($cliente['email']) ?></td>
                    <td><?= date('d/m/Y', strtotime($cliente['data_cadastro'])) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php require __DIR__ . '/../layouts/rodape.php'; ?>
