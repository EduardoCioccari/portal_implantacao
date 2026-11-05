<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Core\Database;

$pdo = database::conexao();

$resultado = $pdo->query("SELECT * FROM usuarios");
$linha = $resultado->fetchAll();

echo $linha[0]['nome'];