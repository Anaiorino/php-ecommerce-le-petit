<?php

require 'config.php';

/* PROTEÇÃO ADMIN */
if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

/* VALIDAÇÕES */
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$status = trim($_GET['s'] ?? '');

/* STATUS PERMITIDOS */
$permitidos = [
    'pendente',
    'processando',
    'enviado',
    'entregue',
    'cancelado'
];

/* SE ID INVÁLIDO */
if ($id <= 0) {
    header("Location: admin_pedidos.php");
    exit;
}

/* ATUALIZA */
if (in_array($status, $permitidos)) {

    $stmt = $pdo->prepare("
        UPDATE orders
        SET status = ?
        WHERE id = ?
    ");

    $stmt->execute([$status, $id]);
}

/* REDIRECIONA */
header("Location: admin_pedidos.php");
exit;