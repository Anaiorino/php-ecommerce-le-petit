
<?php

if (session_status() === PHP_SESSION_NONE) session_start();

$cart_count = 0;

if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $qtd) {
        $cart_count += (int)$qtd;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Le Petit Papelaria</title>
<link rel="stylesheet" href="style.css?v=5">
</head>
<body>

<header class="header">
<div class="topbar">

<a href="index.php" class="logo">
<img src="uploads/logo.png" alt="Logo">
</a>

<nav class="menu">
<a href="index.php">Início</a>
<a href="index.php?promo=1">Promoção</a>
<a href="index.php">Catálogo</a>
<a href="contato.php">Contato</a>
<a href="sobre.php">Sobre</a>
</nav>

<div class="actions">

<a href="cart.php" class="cart-btn">
🛒
<span><?= $cart_count ?></span>
</a>

<?php if (!empty($_SESSION['user'])): ?>

<span class="welcome">
Olá, <?= htmlspecialchars($_SESSION['user']['nome']) ?>
</span>

<?php if ($_SESSION['user']['role'] === 'admin'): ?>
<a class="btn-outline" href="admin.php">Admin</a>
<?php endif; ?>

<a class="btn" href="logout.php">Sair</a>

<?php else: ?>

<a class="btn-outline" href="login.php">Entrar</a>
<a class="btn" href="register.php">Cadastrar</a>

<?php endif; ?>

</div>
</div>

<div class="search-area">
<form action="index.php" method="get">
<input
type="text"
name="q"
placeholder="Buscar produtos..."
value="<?= htmlspecialchars($_GET['q'] ?? '') ?>"
>
<button>Buscar</button>
</form>
</div>
</header>

<main class="page">

