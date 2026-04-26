<?php

/* ===================================
   CONFIGURAÇÕES DO BANCO
=================================== */
$DB_HOST = 'localhost';
$DB_NAME = 'meditacao_store';
$DB_USER = 'root';
$DB_PASS = '';

/* ===================================
   CONEXÃO PDO
=================================== */
try {

    $pdo = new PDO(
        "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4",
        $DB_USER,
        $DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false
        ]
    );

} catch (PDOException $e) {

    die("Erro ao conectar no banco de dados.");

}

/* ===================================
   SESSÃO
=================================== */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* ===================================
   CONSTANTES
=================================== */
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('UPLOAD_URL', 'uploads/');

/* ===================================
   FUNÇÕES GERAIS
=================================== */

/* Escape HTML */
function e($texto)
{
    return htmlspecialchars($texto ?? '', ENT_QUOTES, 'UTF-8');
}

/* Redirecionamento */
function redirect($url)
{
    header("Location: {$url}");
    exit;
}

/* Usuário logado */
function isLogged()
{
    return !empty($_SESSION['user']);
}

/* Admin */
function isAdmin()
{
    return !empty($_SESSION['user']) &&
           $_SESSION['user']['role'] === 'admin';
}

/* ===================================
   CARRINHO -> BANCO PARA SESSÃO
=================================== */
function syncCartDBtoSession($pdo)
{
    if (!isLogged()) {
        return;
    }

    $_SESSION['cart'] = [];

    $stmt = $pdo->prepare("
        SELECT product_id, quantity
        FROM cart_items
        WHERE user_id = ?
    ");

    $stmt->execute([
        $_SESSION['user']['id']
    ]);

    $itens = $stmt->fetchAll();

    foreach ($itens as $item) {

        $_SESSION['cart'][$item['product_id']] =
            (int) $item['quantity'];
    }
}

/* ===================================
   CARRINHO -> SESSÃO PARA BANCO
=================================== */
function syncCartSessionToDB($pdo)
{
    if (!isLogged()) {
        return;
    }

    $userId = $_SESSION['user']['id'];

    $stmt = $pdo->prepare("
        DELETE FROM cart_items
        WHERE user_id = ?
    ");

    $stmt->execute([$userId]);

    if (empty($_SESSION['cart'])) {
        return;
    }

    $stmt = $pdo->prepare("
        INSERT INTO cart_items
        (user_id, product_id, quantity)
        VALUES (?, ?, ?)
    ");

    foreach ($_SESSION['cart'] as $productId => $qty) {

        $stmt->execute([
            $userId,
            (int) $productId,
            (int) $qty
        ]);
    }
}