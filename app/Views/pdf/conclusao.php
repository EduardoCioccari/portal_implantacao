<?php
/** @var array $cliente */
/** @var array $analista */
/** @var array $itens */
/** @var string|null $assinaturaSrc */
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: "DejaVu Sans", sans-serif; font-size: 12px; color: #222; }
        h1 { font-size: 18px; text-align: center; margin-bottom: 4px; }
        .subtitulo { text-align: center; color: #666; margin-top: 0; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #999; padding: 6px; text-align: left; }
        th { background: #e9ecef; }
        .assinaturas { width: 100%; margin-top: 50px; border: none; }
        .assinaturas td { border: none; text-align: center; vertical-align: bottom; width: 50%; }
        .linha { border-top: 1px solid #222; padding-top: 4px; margin: 0 20px; }
        .imagem-assinatura { max-height: 70px; max-width: 220px; }
    </style>
</head>
<body>
    <h1>Termo de Conclusão de Implantação</h1>
    <p class="subtitulo">Portal do Cliente – Gestão de Implantação</p>

    <p><strong>Cliente:</strong> <?= htmlspecialchars($cliente['nome']) ?></p>
    <p><strong>Analista responsável:</strong> <?= htmlspecialchars($analista['nome']) ?></p>
    <p><strong>Data de emissão:</strong> <?= date('d/m/Y') ?></p>

    <p>Declaramos que todos os itens da implantação abaixo foram executados pelo cliente e validados pelo analista responsável.</p>

    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Prazo previsto</th>
                <th>Concluído em</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($itens as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['titulo']) ?></td>
                    <td><?= date('d/m/Y', strtotime($item['prazo_previsto'])) ?></td>
                    <td><?= $item['data_conclusao'] ? date('d/m/Y', strtotime($item['data_conclusao'])) : '-' ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <table class="assinaturas">
        <tr>
            <td>
                <?php if ($assinaturaSrc): ?>
                    <img src="<?= $assinaturaSrc ?>" class="imagem-assinatura"><br>
                <?php endif; ?>
                <div class="linha"><?= htmlspecialchars($analista['nome']) ?><br>Analista</div>
            </td>
            <td>
                <div class="linha"><?= htmlspecialchars($cliente['nome']) ?><br>Cliente</div>
            </td>
        </tr>
    </table>
</body>
</html>
