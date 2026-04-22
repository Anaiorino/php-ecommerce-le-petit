
<?php


$DB_HOST = 'localhost';
$DB_NAME = 'meditacao_store';
$DB_USER = 'root';
$DB_PASS = '';

try {
    $pdo = new PDO(
        "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4",
        $DB_USER,
        $DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die("Erro no banco: " . $e->getMessage());
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('UPLOAD_DIR', 'uploads/');

function syncCartDBtoSession($pdo)
{
    if (empty($_SESSION['user'])) return;

    $_SESSION['cart'] = [];

    $stmt = $pdo->prepare("
        SELECT product_id, quantity
        FROM cart_items
        WHERE user_id = ?
    ");

    $stmt->execute([$_SESSION['user']['id']]);

    foreach ($stmt->fetchAll() as $item) {
        $_SESSION['cart'][$item['product_id']] = $item['quantity'];
    }
}

function syncCartSessionToDB($pdo)
{
    if (empty($_SESSION['user'])) return;

    $uid = $_SESSION['user']['id'];

    $pdo->prepare("DELETE FROM cart_items WHERE user_id=?")->execute([$uid]);

    if (empty($_SESSION['cart'])) return;

    foreach ($_SESSION['cart'] as $pid => $qty) {
        $stmt = $pdo->prepare("
            INSERT INTO cart_items(user_id, product_id, quantity)
            VALUES(?,?,?)
        ");

        $stmt->execute([$uid, $pid, $qty]);
    }
}
