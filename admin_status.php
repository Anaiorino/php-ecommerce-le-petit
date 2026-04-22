
<?php

require 'config.php';

if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    exit;
}

$id = (int) $_GET['id'];
$status = $_GET['s'];

$permitidos = [
    'pendente',
    'processando',
    'enviado',
    'entregue'
];

if (in_array($status, $permitidos)) {

    $stmt = $pdo->prepare("
    UPDATE orders
    SET status=?
    WHERE id=?
    ");

    $stmt->execute([$status, $id]);
}

header("Location: admin_pedidos.php");
exit;

