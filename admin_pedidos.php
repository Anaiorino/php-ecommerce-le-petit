
<?php

require 'config.php';

if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$pedidos = $pdo->query("
SELECT orders.*, users.nome
FROM orders
JOIN users ON users.id = orders.user_id
ORDER BY orders.id DESC
")->fetchAll();

require 'header.php';
?>

<h2>Pedidos</h2>

<table class="table">

<tr>
<th>ID</th>
<th>Cliente</th>
<th>Total</th>
<th>Status</th>
<th>Ações</th>
</tr>

<?php foreach($pedidos as $p): ?>

<tr>

<td>#<?= $p['id'] ?></td>
<td><?= $p['nome'] ?></td>
<td>R$ <?= number_format($p['total'],2,',','.') ?></td>
<td><?= $p['status'] ?></td>

<td>
<a href="admin_status.php?id=<?= $p['id'] ?>&s=processando">Processar</a> |
<a href="admin_status.php?id=<?= $p['id'] ?>&s=enviado">Enviar</a> |
<a href="admin_status.php?id=<?= $p['id'] ?>&s=entregue">Entregar</a>
</td>

</tr>

<?php endforeach; ?>

</table>

<?php require 'footer.php'; ?>

