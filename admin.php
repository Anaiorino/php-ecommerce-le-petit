
<?php

require 'config.php';

if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

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

$products = $pdo->query("
SELECT * FROM products
ORDER BY id DESC
")->fetchAll();

require 'header.php';
?>

<h2 style="margin-bottom:20px;">Painel Admin</h2>

<a href="add_product.php" class="btn">+ Novo Produto</a>
<a href="admin_pedidos.php" class="btn">Pedidos</a>

<table class="table" style="margin-top:20px;">

<tr>
<th>ID</th>
<th>Imagem</th>
<th>Produto</th>
<th>Preço</th>
<th>Ações</th>
</tr>

<?php foreach($products as $p): ?>

<tr>

<td><?= $p['id'] ?></td>

<td>
<?php if($p['imagem']): ?>
<img src="uploads/<?= $p['imagem'] ?>" width="60">
<?php endif; ?>
</td>

<td><?= htmlspecialchars($p['nome']) ?></td>

<td>R$ <?= number_format($p['preco'],2,',','.') ?></td>

<td>
<a href="admin.php?delete=<?= $p['id'] ?>"
onclick="return confirm('Excluir produto?')">
Excluir
</a>
</td>

</tr>

<?php endforeach; ?>

</table>

<?php require 'footer.php'; ?>

