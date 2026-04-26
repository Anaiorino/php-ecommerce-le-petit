<?php

require 'config.php';

/* INICIA CARRINHO */
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

/* AÇÃO */
$action = $_GET['action'] ?? '';

/* ===============================
   ADICIONAR PRODUTO
================================ */
if ($action === 'add') {

    $id  = (int)($_POST['id'] ?? 0);
    $qty = max(1, (int)($_POST['qty'] ?? 1));

    if ($id > 0) {

        /* verifica se produto existe */
        $stmt = $pdo->prepare("SELECT id FROM products WHERE id=? LIMIT 1");
        $stmt->execute([$id]);

        if ($stmt->fetch()) {

            if (!isset($_SESSION['cart'][$id])) {
                $_SESSION['cart'][$id] = 0;
            }

            $_SESSION['cart'][$id] += $qty;

            /* limite por item */
            if ($_SESSION['cart'][$id] > 99) {
                $_SESSION['cart'][$id] = 99;
            }
        }
    }

    syncCartSessionToDB($pdo);

    header("Location: cart.php");
    exit;
}

/* ===============================
   REMOVER ITEM
================================ */
if ($action === 'remove') {

    $id = (int)($_GET['id'] ?? 0);

    if ($id > 0 && isset($_SESSION['cart'][$id])) {
        unset($_SESSION['cart'][$id]);
    }

    syncCartSessionToDB($pdo);

    header("Location: cart.php");
    exit;
}

/* ===============================
   LIMPAR CARRINHO
================================ */
if ($action === 'clear') {

    $_SESSION['cart'] = [];

    syncCartSessionToDB($pdo);

    header("Location: cart.php");
    exit;
}

/* ===============================
   ATUALIZAR QUANTIDADES
================================ */
if ($action === 'update') {

    if (!empty($_POST['qty']) && is_array($_POST['qty'])) {

        foreach ($_POST['qty'] as $id => $qtd) {

            $id  = (int)$id;
            $qtd = (int)$qtd;

            if ($id <= 0) {
                continue;
            }

            if ($qtd <= 0) {

                unset($_SESSION['cart'][$id]);

            } else {

                if ($qtd > 99) {
                    $qtd = 99;
                }

                $_SESSION['cart'][$id] = $qtd;
            }
        }
    }

    syncCartSessionToDB($pdo);

    header("Location: cart.php");
    exit;
}

/* ===============================
   AÇÃO INVÁLIDA
================================ */
header("Location: index.php");
exit;