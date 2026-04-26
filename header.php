<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* ===================================
   CONTADOR DO CARRINHO
=================================== */
$cart_count = 0;

if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $qtd) {
        $cart_count += (int) $qtd;
    }
}

/* ===================================
   USUÁRIO
=================================== */
$user = $_SESSION['user'] ?? null;
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Le Petit Papelaria</title>

<meta
name="description"
content="Loja online de papelaria criativa, materiais escolares e presentes especiais."
>

<link rel="stylesheet" href="style.css?v=8">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

</head>

<body>

<header class="header">

    <!-- TOPO -->
    <div class="topbar">

        <!-- LOGO -->
        <a href="index.php" class="logo">
            <img src="uploads/logo.png" alt="Le Petit Papelaria">
        </a>

        <!-- MENU -->
        <nav class="menu">

            <a href="index.php">Início</a>

           

            <a href="contato.php">Contato</a>


        </nav>

        <!-- AÇÕES -->
        <div class="actions">

            <!-- CARRINHO -->
            <a href="cart.php" class="cart-btn" title="Carrinho">

                🛒

                <?php if ($cart_count > 0): ?>
                    <span><?= $cart_count ?></span>
                <?php endif; ?>

            </a>

            <?php if ($user): ?>

                <!-- USER -->
                <span class="welcome">
                    Olá, <?= htmlspecialchars($user['nome']) ?>
                </span>

                <?php if (($user['role'] ?? '') === 'admin'): ?>
                    <a href="admin.php" class="btn-outline">
                        Admin
                    </a>
                <?php endif; ?>

                <a href="logout.php" class="btn">
                    Sair
                </a>

            <?php else: ?>

                <a href="login.php" class="btn-outline">
                    Entrar
                </a>

                <a href="register.php" class="btn">
                    Criar Conta
                </a>

            <?php endif; ?>

        </div>

    </div>

    <!-- BUSCA -->
    <div class="search-area">

        <form action="index.php" method="get">

            <input
                type="text"
                name="q"
                placeholder="Buscar produtos..."
                value="<?= htmlspecialchars($_GET['q'] ?? '') ?>"
            >

            <button type="submit">
                Buscar
            </button>

        </form>

    </div>

</header>

<main class="page">