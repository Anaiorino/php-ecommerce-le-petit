<?php

require 'config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'header.php';

/* ===============================
   VERIFICA CARRINHO
================================= */
if (empty($_SESSION['cart'])) {
?>

<div class="auth-box">
    <h2>Carrinho vazio</h2>

    <p style="text-align:center; margin-top:15px; color:#666;">
        Adicione produtos antes de finalizar sua compra.
    </p>

    <a href="index.php" class="btn full" style="margin-top:20px;">
        Voltar para loja
    </a>
</div>

<?php
require 'footer.php';
exit;
}
?>

<div class="auth-box pix-box">

<h2>Pagamento via PIX</h2>

<p class="pix-text">
Escaneie o QR Code abaixo para realizar o pagamento.
Após pagar, envie o comprovante para confirmação.
</p>

<div class="pix-qrcode">

<img src="uploads/qrcode-pix.png" alt="QR Code PIX">

</div>

<form
method="post"
action="finalizar_pedido.php"
enctype="multipart/form-data"
class="pix-form"
>

<label>Enviar comprovante</label>

<input
type="file"
name="comprovante"
accept="image/*,.pdf"
required
>

<button class="btn full">
Confirmar Pagamento
</button>

</form>

<div class="pix-info">
Prazo de confirmação: até 30 minutos após envio.
</div>

</div>

<?php require 'footer.php'; ?>