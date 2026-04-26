<?php

require 'config.php';

if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

/* EXCLUIR PRODUTO */
if (isset($_GET['delete'])) {

    $id = (int) $_GET['delete'];

    $stmt = $pdo->prepare("SELECT imagem FROM products WHERE id=?");
    $stmt->execute([$id]);
    $img = $stmt->fetchColumn();

    if ($img && file_exists("uploads/" . $img)) {
        unlink("uploads/" . $img);
    }

    $stmt = $pdo->prepare("DELETE FROM products WHERE id=?");
    $stmt->execute([$id]);

    header("Location: admin.php");
    exit;
}

/* PRODUTOS */
$products = $pdo->query("
SELECT * FROM products
ORDER BY id DESC
")->fetchAll();

/* ESTATÍSTICAS */
$totalProdutos = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$totalPedidos = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$pedidosPendentes = $pdo->query("SELECT COUNT(*) FROM orders WHERE status='pendente'")->fetchColumn();

require 'header.php';
?>

<style>
.admin-page{
max-width:1400px;
margin:auto;
}

.admin-title{
font-size:34px;
font-weight:700;
color:#d43370;
margin-bottom:25px;
}

.admin-top{
display:flex;
justify-content:space-between;
align-items:center;
gap:15px;
flex-wrap:wrap;
margin-bottom:30px;
}

.admin-actions{
display:flex;
gap:10px;
flex-wrap:wrap;
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
font-size:32px;
font-weight:700;
color:#d43370;
}

.admin-box{
background:#fff;
padding:25px;
border-radius:20px;
box-shadow:0 10px 25px rgba(0,0,0,.08);
overflow-x:auto;
}

.table{
width:100%;
border-collapse:collapse;
min-width:700px;
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

.prod-img{
width:65px;
height:65px;
object-fit:cover;
border-radius:12px;
border:1px solid #eee;
}

.action-delete{
background:#ffefef;
color:#c60000;
padding:8px 14px;
border-radius:10px;
font-weight:bold;
display:inline-block;
}

.action-delete:hover{
opacity:.8;
}

@media(max-width:900px){

.admin-title{
font-size:26px;
}

.table{
min-width:600px;
}

}
</style>

<div class="admin-page">

<div class="admin-top">

<div class="admin-title">
Painel Administrativo
</div>

<div class="admin-actions">
<a href="add_product.php" class="btn">+ Novo Produto</a>
<a href="admin_pedidos.php" class="btn-outline">Pedidos</a>
</div>

</div>

<div class="stats-grid">

<div class="stat-card">
<div class="stat-label">Produtos Cadastrados</div>
<div class="stat-number"><?= $totalProdutos ?></div>
</div>

<div class="stat-card">
<div class="stat-label">Pedidos Totais</div>
<div class="stat-number"><?= $totalPedidos ?></div>
</div>

<div class="stat-card">
<div class="stat-label">Pedidos Pendentes</div>
<div class="stat-number"><?= $pedidosPendentes ?></div>
</div>

</div>

<div class="admin-box">

<table class="table">

<tr>
<th>ID</th>
<th>Imagem</th>
<th>Produto</th>
<th>Preço</th>
<th>Ações</th>
</tr>

<?php foreach($products as $p): ?>

<tr>

<td>#<?= $p['id'] ?></td>

<td>
<?php if($p['imagem']): ?>
<img src="uploads/<?= htmlspecialchars($p['imagem']) ?>" class="prod-img">
<?php else: ?>
-
<?php endif; ?>
</td>

<td>
<strong><?= htmlspecialchars($p['nome']) ?></strong>
</td>

<td>
R$ <?= number_format($p['preco'],2,',','.') ?>
</td>

<td>
<a
href="admin.php?delete=<?= $p['id'] ?>"
class="action-delete"
onclick="return confirm('Excluir produto?')"
>
Excluir
</a>
</td>

</tr>

<?php endforeach; ?>

</table>

</div>

</div>

<?php require 'footer.php'; ?>