
<?php

require 'config.php';
require 'header.php';

$items = [];
$total = 0;

if (!empty($_SESSION['cart'])) {

    $ids = array_keys($_SESSION['cart']);
    $marks = implode(',', array_fill(0, count($ids), '?'));

    $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($marks)");
    $stmt->execute($ids);

    $items = $stmt->fetchAll();
}
?>

<h2 style="margin-bottom:20px;">Seu Carrinho</h2>

<?php if (empty($items)): ?>

<div class="empty-cart">
Seu carrinho está vazio.
</div>

<?php else: ?>

<form action="cart_actions.php?action=update" method="post">

<table class="cart-table">

<tr>
<th>Produto</th>
<th>Preço</th>
<th>Qtd</th>
<th>Subtotal</th>
<th></th>
</tr>

<?php foreach ($items as $item):

$qtd = $_SESSION['cart'][$item['id']];
$subtotal = $item['preco'] * $qtd;
$total += $subtotal;

?>

<tr>

<td>
<div class="cart-product">

<?php if ($item['imagem']): ?>
<img src="uploads/<?= htmlspecialchars($item['imagem']) ?>">
<?php endif; ?>

<div>
<strong><?= htmlspecialchars($item['nome']) ?></strong>
</div>

</div>
</td>

<td>
R$ <?= number_format($item['preco'],2,',','.') ?>
</td>

<td>
<input
type="number"
name="qty[<?= $item['id'] ?>]"
value="<?= $qtd ?>"
min="1"
style="width:70px;"
>
</td>

<td>
R$ <?= number_format($subtotal,2,',','.') ?>
</td>

<td>
<a href="cart_actions.php?action=remove&id=<?= $item['id'] ?>">
Remover
</a>
</td>

</tr>

<?php endforeach; ?>

</table>

<div class="cart-bottom">

<div class="cart-total">
Total:
<strong>
R$ <?= number_format($total,2,',','.') ?>
</strong>
</div>

<div class="cart-buttons">
<button class="btn">Atualizar</button>

<a href="cart_actions.php?action=clear" class="btn-outline">
Limpar
</a>

<a href="checkout.php" class="btn">
Finalizar Compra
</a>
</div>

</div>

</form>

<?php endif; ?>

<?php require 'footer.php'; ?>
