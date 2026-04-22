
<?php

require 'config.php';
require 'header.php';

if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

$ids = array_keys($_SESSION['cart']);
$marks = implode(',', array_fill(0, count($ids), '?'));

$stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($marks)");
$stmt->execute($ids);

$items = $stmt->fetchAll();

$total = 0;

foreach ($items as $item) {
    $total += $item['preco'] * $_SESSION['cart'][$item['id']];
}
?>

<h2 style="margin-bottom:25px;">Finalizar Compra</h2>

<div class="checkout-grid">

<div class="checkout-box">

<form action="finalizar_pedido.php" method="post" enctype="multipart/form-data">

<label>Nome Completo</label>
<input type="text" name="nome" required>

<label>Telefone</label>
<input type="text" name="telefone" required>

<label>CEP</label>
<input type="text" name="cep" required>

<label>Endereço</label>
<input type="text" name="endereco" required>

<label>Número</label>
<input type="text" name="numero" required>

<label>Bairro</label>
<input type="text" name="bairro" required>

<label>Cidade</label>
<input type="text" name="cidade" required>

<label>Observações</label>
<textarea name="obs"></textarea>

<h3 style="margin:25px 0 10px;">Pagamento PIX</h3>

<img src="uploads/qrcodepix.jpeg" class="pix-img">

<label>Enviar comprovante</label>
<input type="file" name="comprovante" required>

<button class="btn full" style="margin-top:15px;">
Confirmar Pedido
</button>

</form>

</div>

<div class="checkout-box">

<h3>Resumo do Pedido</h3>

<?php foreach ($items as $item):

$qtd = $_SESSION['cart'][$item['id']];
$subtotal = $item['preco'] * $qtd;

?>

<div class="resume-line">
<span><?= htmlspecialchars($item['nome']) ?> x<?= $qtd ?></span>
<span>R$ <?= number_format($subtotal,2,',','.') ?></span>
</div>

<?php endforeach; ?>

<hr style="margin:15px 0;">

<div class="resume-total">
Total:
<strong>
R$ <?= number_format($total,2,',','.') ?>
</strong>
</div>

</div>

</div>

<?php require 'footer.php'; ?>

