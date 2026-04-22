
<?php

require 'config.php';

if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome = trim($_POST['nome']);
    $descricao = trim($_POST['descricao']);
    $categoria = trim($_POST['categoria']);
    $marca = trim($_POST['marca']);
    $preco = $_POST['preco'];

    $imagem = null;

    if (!empty($_FILES['imagem']['name'])) {

        $ext = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
        $imagem = time() . '.' . $ext;

        move_uploaded_file(
            $_FILES['imagem']['tmp_name'],
            'uploads/' . $imagem
        );
    }

    $stmt = $pdo->prepare("
    INSERT INTO products
    (nome,descricao,categoria,marca,preco,imagem)
    VALUES (?,?,?,?,?,?)
    ");

    $stmt->execute([
        $nome,
        $descricao,
        $categoria,
        $marca,
        $preco,
        $imagem
    ]);

    $msg = "Produto cadastrado.";
}

require 'header.php';
?>

<div class="auth-box" style="width:650px;">

<h2>Novo Produto</h2>

<?php if($msg): ?>
<div class="success"><?= $msg ?></div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">

<label>Nome</label>
<input type="text" name="nome" required>

<label>Descrição</label>
<textarea name="descricao"></textarea>

<label>Categoria</label>
<input type="text" name="categoria">

<label>Marca</label>
<input type="text" name="marca">

<label>Preço</label>
<input type="number" step="0.01" name="preco" required>

<label>Imagem</label>
<input type="file" name="imagem">

<button class="btn full">Salvar Produto</button>

</form>

</div>

<?php require 'footer.php'; ?>

