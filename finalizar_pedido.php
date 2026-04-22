
<?php

require 'config.php';

if (empty($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

$userId = $_SESSION['user']['id'];

$nome = trim($_POST['nome'] ?? '');
$telefone = trim($_POST['telefone'] ?? '');
$cep = trim($_POST['cep'] ?? '');
$endereco = trim($_POST['endereco'] ?? '');
$numero = trim($_POST['numero'] ?? '');
$bairro = trim($_POST['bairro'] ?? '');
$cidade = trim($_POST['cidade'] ?? '');
$obs = trim($_POST['obs'] ?? '');

if (
    $nome === '' || $telefone === '' || $cep === '' ||
    $endereco === '' || $numero === '' ||
    $bairro === '' || $cidade === ''
) {
    header("Location: checkout.php");
    exit;
}

// upload comprovante
$arquivo = $_FILES['comprovante'] ?? null;
$comprovante = null;

if ($arquivo && $arquivo['error'] === 0) {

    $ext = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));

    $permitidos = ['jpg','jpeg','png','webp','pdf'];

    if (in_array($ext, $permitidos)) {

        $comprovante = time() . '_' . uniqid() . '.' . $ext;

        move_uploaded_file(
            $arquivo['tmp_name'],
            'uploads/' . $comprovante
        );
    }
}

// calcular total
$total = 0;

foreach ($_SESSION['cart'] as $pid => $qty) {

    $stmt = $pdo->prepare("SELECT preco FROM products WHERE id=?");
    $stmt->execute([$pid]);

    $preco = $stmt->fetchColumn();

    if ($preco) {
        $total += $preco * $qty;
    }
}

// criar pedido
$stmt = $pdo->prepare("
INSERT INTO orders
(user_id,total,comprovante,status)
VALUES (?,?,?,'pendente')
");

$stmt->execute([
    $userId,
    $total,
    $comprovante
]);

$orderId = $pdo->lastInsertId();

// itens
foreach ($_SESSION['cart'] as $pid => $qty) {

    $stmt = $pdo->prepare("SELECT preco FROM products WHERE id=?");
    $stmt->execute([$pid]);

    $preco = $stmt->fetchColumn();

    $stmt = $pdo->prepare("
    INSERT INTO order_items
    (order_id,product_id,qty,price)
    VALUES (?,?,?,?)
    ");

    $stmt->execute([
        $orderId,
        $pid,
        $qty,
        $preco
    ]);
}

// limpar carrinho
$_SESSION['cart'] = [];
syncCartSessionToDB($pdo);

header("Location: pedido_realizado.php?id=$orderId");

