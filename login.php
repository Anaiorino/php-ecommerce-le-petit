
<?php

require 'config.php';

if (!empty($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
    $stmt->execute([$username]);

    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {

        $_SESSION['user'] = [
            'id' => $user['id'],
            'nome' => $user['nome'],
            'username' => $user['username'],
            'role' => $user['role']
        ];

        syncCartDBtoSession($pdo);

        header("Location: index.php");
        exit;

    } else {
        $erro = "Usuário ou senha inválidos.";
    }
}

require 'header.php';
?>

<div class="auth-box">
    <h2>Entrar</h2>

    <?php if($erro): ?>
        <div class="error"><?= $erro ?></div>
    <?php endif; ?>

    <form method="post">

        <label>Usuário</label>
        <input type="text" name="username" required>

        <label>Senha</label>
        <input type="password" name="password" required>

        <button class="btn full">Entrar</button>

    </form>

    <p style="margin-top:15px;">
        Não possui conta?
        <a href="register.php">Cadastre-se</a>
    </p>
</div>

<?php require 'footer.php'; ?>
