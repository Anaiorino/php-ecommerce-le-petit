
<?php

require 'config.php';
require 'header.php';

$id = (int)($_GET['id'] ?? 0);
?>

<div class="success-page">

<h1>Pedido realizado com sucesso! 🎉</h1>

<p>
Seu pedido número
<strong>#<?= $id ?></strong>
foi enviado para análise de pagamento.
</p>

<p>
Em breve você receberá atualização no painel.
</p>

<a href="index.php" class="btn">
Voltar para loja
</a>

</div>

<?php require 'footer.php'; ?>
