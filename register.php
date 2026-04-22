
<?php

require 'config.php';

$erro = '';
$ok = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome = trim($_POST['nome'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($nome === '' || $username === '' || $password === '') {
        $erro = "Preencha todos os campos.";
    } else {

        $stmt = $pdo->prepare("SELECT id FROM users WHERE username=?");
        $stmt->execute([$username]);

        if ($stmt->fetch()) {
            $erro = "Usuário já existe.";
        } else {

            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("
                INSERT INTO users(nome,username,password,role)
                VALUES (?,?,?,'user')
            ");

            $stmt->execute([
                $nome,
                $username,
                $hash
            ]);

            $ok = "Conta criada com sucesso.";
        }
    }
}

require 'header.php';
?>

<div class="auth-box">

<h2>Criar Conta</h2>

<?php if($erro): ?>
<div class="error"><?= $erro ?></div>
<?php endif; ?>

<?php if($ok): ?>
<div class="success"><?= $ok ?></div>
<?php endif; ?>

<form method="post">

<label>Nome Completo</label>
<input type="text" name="nome" required>

<label>Usuário</label>
<input type="text" name="username" required>

<label>Senha</label>
<input type="password" name="password" required>

<button class="btn full">Cadastrar</button>

</form>

</div>

<?php require 'footer.php'; ?>

