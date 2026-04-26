<?php

require 'config.php';

/* ===================================
   VALIDA LOGIN
=================================== */
if (!isLogged()) {
    redirect('login.php');
}

/* ===================================
   VALIDA CARRINHO
=================================== */
if (empty($_SESSION['cart'])) {
    redirect('cart.php');
}

/* ===================================
   SOMENTE POST
=================================== */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('checkout.php');
}

/* ===================================
   DADOS DO CLIENTE
=================================== */
$userId    = $_SESSION['user']['id'];

$nome      = trim($_POST['nome'] ?? '');
$telefone  = trim($_POST['telefone'] ?? '');
$cep       = trim($_POST['cep'] ?? '');
$endereco  = trim($_POST['endereco'] ?? '');
$numero    = trim($_POST['numero'] ?? '');
$bairro    = trim($_POST['bairro'] ?? '');
$cidade    = trim($_POST['cidade'] ?? '');
$obs       = trim($_POST['obs'] ?? '');

/* ===================================
   CAMPOS OBRIGATÓRIOS
=================================== */
if (
    $nome === '' ||
    $telefone === '' ||
    $cep === '' ||
    $endereco === '' ||
    $numero === '' ||
    $bairro === '' ||
    $cidade === ''
) {
    redirect('checkout.php');
}

/* ===================================
   UPLOAD COMPROVANTE
=================================== */
$comprovante = null;

if (!empty($_FILES['comprovante']['name'])) {

    $arquivo = $_FILES['comprovante'];

    if ($arquivo['error'] === 0) {

        $ext = strtolower(
            pathinfo($arquivo['name'], PATHINFO_EXTENSION)
        );

        $permitidos = [
            'jpg',
            'jpeg',
            'png',
            'webp',
            'pdf'
        ];

        if (in_array($ext, $permitidos)) {

            $novoNome =
                'pix_' .
                time() .
                '_' .
                bin2hex(random_bytes(4)) .
                '.' .
                $ext;

            move_uploaded_file(
                $arquivo['tmp_name'],
                UPLOAD_DIR . $novoNome
            );

            $comprovante = $novoNome;
        }
    }
}

/* ===================================
   BUSCAR PRODUTOS DO CARRINHO
=================================== */
$ids = array_keys($_SESSION['cart']);

$marks = implode(',', array_fill(0, count($ids), '?'));

$stmt = $pdo->prepare("
    SELECT id, preco
    FROM products
    WHERE id IN ($marks)
");

$stmt->execute($ids);

$produtos = $stmt->fetchAll();

/* ===================================
   CALCULAR TOTAL
=================================== */
$total = 0;

$mapaPrecos = [];

foreach ($produtos as $produto) {

    $mapaPrecos[$produto['id']] = $produto['preco'];

    $qtd = (int) $_SESSION['cart'][$produto['id']];

    $total += $produto['preco'] * $qtd;
}

/* ===================================
   TRANSAÇÃO
=================================== */
try {

    $pdo->beginTransaction();

    /* PEDIDO */
    $stmt = $pdo->prepare("
        INSERT INTO orders
        (
            user_id,
            total,
            comprovante,
            status
        )
        VALUES (?, ?, ?, 'pendente')
    ");

    $stmt->execute([
        $userId,
        $total,
        $comprovante
    ]);

    $orderId = $pdo->lastInsertId();

    /* ITENS */
    $stmtItem = $pdo->prepare("
        INSERT INTO order_items
        (
            order_id,
            product_id,
            qty,
            price
        )
        VALUES (?, ?, ?, ?)
    ");

    foreach ($_SESSION['cart'] as $productId => $qtd) {

        $preco = $mapaPrecos[$productId] ?? 0;

        $stmtItem->execute([
            $orderId,
            $productId,
            $qtd,
            $preco
        ]);
    }

    $pdo->commit();

} catch (Exception $e) {

    $pdo->rollBack();
    redirect('checkout.php');
}

/* ===================================
   LIMPA CARRINHO
=================================== */
$_SESSION['cart'] = [];

syncCartSessionToDB($pdo);

/* ===================================
   REDIRECIONA
=================================== */
redirect("pedido_realizado.php?id={$orderId}");