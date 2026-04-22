
<?php

require 'config.php';

if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$action = $_GET['action'] ?? '';

if ($action === 'add') {

    $id = (int)($_POST['id'] ?? 0);
    $qty = max(1, (int)($_POST['qty'] ?? 1));

    if ($id > 0) {
        if (!isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id] = 0;
        }

        $_SESSION['cart'][$id] += $qty;
    }

    syncCartSessionToDB($pdo);

    header("Location: cart.php");
    exit;
}

if ($action === 'remove') {

    $id = (int)($_GET['id'] ?? 0);

    unset($_SESSION['cart'][$id]);

    syncCartSessionToDB($pdo);

    header("Location: cart.php");
    exit;
}

if ($action === 'clear') {

    $_SESSION['cart'] = [];

    syncCartSessionToDB($pdo);

    header("Location: cart.php");
    exit;
}

if ($action === 'update') {

    foreach ($_POST['qty'] ?? [] as $id => $qtd) {

        $id = (int)$id;
        $qtd = (int)$qtd;

        if ($qtd <= 0) {
            unset($_SESSION['cart'][$id]);
        } else {
            $_SESSION['cart'][$id] = $qtd;
        }
    }

    syncCartSessionToDB($pdo);

    header("Location: cart.php");
    exit;
}

header("Location:index.php");
exit;
