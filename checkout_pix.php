<?php
require 'config.php';
session_start();
require 'header.php';

if (empty($_SESSION['cart'])) {
    echo "<h2 style='text-align:center;margin-top:140px;'>Seu carrinho está vazio</h2>";
    require 'footer.php';
    exit;
}
?>

<div class="auth-box" style="margin-top:160px;">
    <h2>Pagamento via PIX</h2>

    <p style="text-align:center;color:var(--text-soft);font-size:15px;">
        Escaneie o QR Code abaixo para realizar o pagamento.
    </p>

    <div style="text-align:center;margin:20px 0;">
       <!-- qr code aqui --> <img src="" 
             alt="QR Code PIX"
             style="width:240px;border-radius:16px;box-shadow:var(--shadow);">
    </div>

    <form method="post" action="finalizar_pedido.php" enctype="multipart/form-data">
        <label>Envie o comprovante do PIX</label>
        <input type="file" name="comprovante" accept="image/*" required>

        <button class="btn" style="width:100%;margin-top:10px;">
            Enviar comprovante
        </button>
    </form>
</div>

<?php require 'footer.php'; ?>
