<?php

require 'config.php';

/* PROTEÇÃO ADMIN */
if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

/* PEDIDOS */
$pedidos = $pdo->query("
SELECT orders.*, users.nome
FROM orders
JOIN users ON users.id = orders.user_id
ORDER BY orders.id DESC
")->fetchAll();

/* ESTATÍSTICAS */
$totalPedidos = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$pendentes = $pdo->query("SELECT COUNT(*) FROM orders WHERE status='pendente'")->fetchColumn();
$faturamento = $pdo->query("SELECT IFNULL(SUM(total),0) FROM orders")->fetchColumn();

require 'header.php';
?>

<style>
.admin-orders{
max-width:1400px;
margin:auto;
}

.page-title{
font-size:34px;
font-weight:700;
color:#d43370;
margin-bottom:25px;
}

.stats-grid{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
gap:20px;
margin-bottom:30px;
}

.stat-card{
background:#fff;
padding:22px;
border-radius:18px;
box-shadow:0 8px 20px rgba(0,0,0,.08);
border-left:5px solid #d43370;
}

.stat-label{
font-size:14px;
color:#777;
margin-bottom:8px;
}

.stat-number{
font-size:30px;
font-weight:700;
color:#d43370;
}

.box-table{
background:#fff;
padding:25px;
border-radius:20px;
box-shadow:0 10px 25px rgba(0,0,0,.08);
overflow-x:auto;
}

.table{
width:100%;
border-collapse:collapse;
min-width:950px;
}

.table th{
background:#d43370;
color:#fff;
padding:14px;
text-align:left;
font-size:14px;
}

.table td{
padding:14px;
border-bottom:1px solid #f1f1f1;
vertical-align:middle;
}

.table tr:hover{
background:#fff7fa;
}

.badge{
padding:7px 12px;
border-radius:999px;
font-size:13px;
font-weight:700;
display:inline-block;
text-transform:capitalize;
}

.pendente{background:#fff1cc;color:#9a7000;}
.processando{background:#e7f0ff;color:#0d56c7;}
.enviado{background:#e9fbe9;color:#1a7f37;}
.entregue{background:#e5fff1;color:#00884d;}
.cancelado{background:#ffe8e8;color:#c40000;}

.actions{
display:flex;
gap:8px;
flex-wrap:wrap;
}

.btn-mini{
padding:8px 12px;
border-radius:10px;
font-size:13px;
font-weight:700;
background:#f3f3f3;
}

.btn-mini:hover{
opacity:.85;
}

@media(max-width:900px){

.page-title{
font-size:26px;
}

.table{
min-width:850px;
}

}
</style>

<div class="admin-orders">

<h2 class="page-title">Gestão de Pedidos</h2>

<div class="stats-grid">

<div class="stat-card">
<div class="stat-label">Total de Pedidos</div>
<div class="stat-number"><?= $totalPedidos ?></div>
</div>

<div class="stat-card">
<div class="stat-label">Pendentes</div>
<div class="stat-number"><?= $pendentes ?></div>
</div>

<div class="stat-card">
<div class="stat-label">Faturamento Total</div>
<div class="stat-number">
R$ <?= number_format($faturamento,2,',','.') ?>
</div>
</div>

</div>

<div class="box-table">

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

<td>
<strong><?= htmlspecialchars($p['nome']) ?></strong>
</td>

<td>
R$ <?= number_format($p['total'],2,',','.') ?>
</td>

<td>
<span class="badge <?= $p['status'] ?>">
<?= $p['status'] ?>
</span>
</td>

<td>

<div class="actions">

<a class="btn-mini"
href="admin_status.php?id=<?= $p['id'] ?>&s=processando">
Processar
</a>

<a class="btn-mini"
href="admin_status.php?id=<?= $p['id'] ?>&s=enviado">
Enviar
</a>

<a class="btn-mini"
href="admin_status.php?id=<?= $p['id'] ?>&s=entregue">
Entregar
</a>

<a class="btn-mini"
href="admin_status.php?id=<?= $p['id'] ?>&s=cancelado">
Cancelar
</a>

</div>

</td>

</tr>

<?php endforeach; ?>

</table>

</div>

</div>

<?php require 'footer.php'; ?>