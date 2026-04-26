<?php

/* ===================================
   GERADOR DE HASH DE SENHA
   Uso local / administrativo
=================================== */

$senha = "2025Lepetit";

$hash = password_hash($senha, PASSWORD_DEFAULT);

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gerar Hash</title>

<style>
body{
    font-family:Arial, Helvetica, sans-serif;
    background:#fdf4f8;
    padding:40px;
    color:#333;
}

.box{
    max-width:700px;
    margin:auto;
    background:#fff;
    padding:30px;
    border-radius:18px;
    box-shadow:0 10px 30px rgba(0,0,0,.08);
}

h1{
    color:#d43370;
    margin-bottom:20px;
}

.code{
    background:#f5f5f5;
    padding:15px;
    border-radius:12px;
    word-break:break-all;
    font-size:14px;
    margin-top:10px;
}

.label{
    font-weight:bold;
    margin-top:20px;
}
</style>

</head>
<body>

<div class="box">

<h1>Gerador de Hash</h1>

<div class="label">Senha:</div>
<div class="code"><?= htmlspecialchars($senha) ?></div>

<div class="label">Hash Gerado:</div>
<div class="code"><?= htmlspecialchars($hash) ?></div>

<div class="label">Exemplo SQL:</div>
<div class="code">
UPDATE users SET password = '<?= htmlspecialchars($hash) ?>' WHERE username = 'admin';
</div>

</div>

</body>
</html>