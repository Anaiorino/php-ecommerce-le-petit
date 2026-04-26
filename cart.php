<?php

require 'config.php';
require 'header.php';

$items = [];
$total = 0;

/* BUSCA PRODUTOS DO CARRINHO */
if (!empty($_SESSION['cart'])) {

    $ids = array_keys($_SESSION['cart']);
    $marks = implode(',', array_fill(0, count($ids), '?'));

    $stmt = $pdo->prepare("
        SELECT * FROM products
        WHERE id IN ($marks)
        ORDER BY id DESC
    ");

    $stmt->execute($ids);

    $items = $stmt->fetchAll();
}
?>

<style>
.cart-page{
max-width:1300px;
margin:auto;
}

.cart-title{
font-size:34px;
font-weight:700;
color:#d43370;
margin-bottom:25px;
}

.empty-cart{
background:#fff;
padding:40px;
border-radius:20px;
box-shadow:0 10px 25px rgba(0,0,0,.08);
text-align:center;
font-size:20px;
font-weight:700;
color:#666;
}

.cart-box{
background:#fff;
padding:25px;
border-radius:22px;
box-shadow:0 10px 25px rgba(0,0,0,.08);
overflow-x:auto;
}

.cart-table{
width:100%;
border-collapse:collapse;
min-width:900px;
}

.cart-table th{
background:#d43370;
color:#fff;
padding:14px;
text-align:left;
font-size:14px;
}

.cart-table td{
padding:18px 14px;
border-bottom:1px solid #f1f1f1;
vertical-align:middle;
}

.cart-table tr:hover{
background:#fff7fa;
}

.cart-product{
display:flex;
align-items:center;
gap:15px;
}

.cart-product img{
width:85px;
height:85px;
object-fit:cover;
border-radius:14px;
border:1px solid #eee;
}

.cart-product strong{
font-size:16px;
color:#333;
}

.qty-input{
width:75px;
padding:10px;
border:1px solid #ddd;
border-radius:10px;
text-align:center;
font-weight:700;
}

.remove-link{
color:#c40000;
font-weight:700;
}

.remove-link:hover{
opacity:.7;
}

.cart-bottom{
margin-top:25px;
display:flex;
justify-content:space-between;
align-items:center;
gap:20px;
flex-wrap:wrap;
}

.cart-total{
font-size:28px;
font-weight:700;
color:#d43370;
}

.cart-buttons{
display:flex;
gap:10px;
flex-wrap:wrap;
}

.btn-outline{
display:inline-block;
}

@media(max-width:900px){

.cart-title{
font-size:26px;
}

.cart-table{
min-width:800px;
}

.cart-total{
font-size:24px;
}

}
</style>

<div class="cart-page">

<h2 class="cart-title">Seu Carrinho</h2>

<?php if (empty($items)): ?>

<div class="empty-cart">
Seu carrinho está vazio 🛒
</div>

<?php else: ?>

<form action="cart_actions.php?action=update" method="post">

<div class="cart-box">

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
class="qty-input"
name="qty[<?= $item['id'] ?>]"
value="<?= $qtd ?>"
min="1"
max="99"
>
</td>

<td>
<strong>
R$ <?= number_format($subtotal,2,',','.') ?>
</strong>
</td>

<td>
<a
href="cart_actions.php?action=remove&id=<?= $item['id'] ?>"
class="remove-link"
onclick="return confirm('Remover item?')"
>
Remover
</a>
</td>

</tr>

<?php endforeach; ?>

</table>

</div>

<div class="cart-bottom">

<div class="cart-total">
Total: R$ <?= number_format($total,2,',','.') ?>
</div>

<div class="cart-buttons">

<button class="btn">
Atualizar
</button>

<a
href="cart_actions.php?action=clear"
class="btn-outline"
onclick="return confirm('Limpar carrinho?')"
>
Limpar
</a>

<a href="checkout.php" class="btn">
Finalizar Compra
</a>

</div>

</div>

</form>

<?php endif; ?>

</div>

<?php require 'footer.php'; ?>