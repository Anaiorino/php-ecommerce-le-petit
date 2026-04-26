
<?php

require 'config.php';
require 'header.php';

$q = trim($_GET['q'] ?? '');
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 12;
$offset = ($page - 1) * $perPage;

$whereList = [];
$params = [];

if ($q !== '') {
    $whereList[] = "
        (
            nome LIKE :q OR
            descricao LIKE :q OR
            categoria LIKE :q OR
            marca LIKE :q
        )
    ";

    $params['q'] = "%{$q}%";
}

if (isset($_GET['promo'])) {
    $whereList[] = "promocao = 1";
}

$where = '';

if ($whereList) {
    $where = 'WHERE ' . implode(' AND ', $whereList);
}


// total
$stmt = $pdo->prepare("SELECT COUNT(*) FROM products $where");
$stmt->execute($params);
$total = (int)$stmt->fetchColumn();
$pages = max(1, ceil($total / $perPage));

// produtos
$sql = "SELECT * FROM products $where ORDER BY id DESC LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);

foreach ($params as $k => $v) {
    $stmt->bindValue(":$k", $v);
}

$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

$products = $stmt->fetchAll();
?>

<section class="hero">
    <img src="uploads/hero.gif" alt="Banner" style="width:100%;border-radius:18px;">
</section>

<h2 style="margin:25px 0 15px;">Produtos</h2>

<?php if ($q): ?>
<p style="margin-bottom:20px;">
Resultados para: <strong><?= htmlspecialchars($q) ?></strong>
</p>
<?php endif; ?>

<div class="cards">

<?php foreach ($products as $p): ?>
<div class="card">

<?php if (!empty($p['imagem'])): ?>
<img src="uploads/<?= htmlspecialchars($p['imagem']) ?>" class="product-img">
<?php else: ?>
<div class="no-image">Sem imagem</div>
<?php endif; ?>

<h3><?= htmlspecialchars($p['nome']) ?></h3>

<p class="desc">
<?= htmlspecialchars(mb_strimwidth($p['descricao'],0,90,'...')) ?>
</p>

<div class="price">
R$ <?= number_format($p['preco'],2,',','.') ?>
</div>

<form action="cart_actions.php?action=add" method="post">
<input type="hidden" name="id" value="<?= $p['id'] ?>">
<input type="hidden" name="qty" value="1">

<button class="btn full">
Adicionar ao Carrinho
</button>
</form>

</div>
<?php endforeach; ?>

</div>

<?php if ($pages > 1): ?>
<div class="pagination">

<?php for ($i=1; $i <= $pages; $i++): ?>
<a
href="?q=<?= urlencode($q) ?>&page=<?= $i ?>"
class="<?= $page == $i ? 'active' : '' ?>"
>
<?= $i ?>
</a>
<?php endfor; ?>

</div>
<?php endif; ?>

<?php require 'footer.php'; ?>

