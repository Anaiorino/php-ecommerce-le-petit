<?php

require 'config.php';

/* PROTEÇÃO ADMIN */
if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$msg = '';
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome = trim($_POST['nome'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $categoria = trim($_POST['categoria'] ?? '');
    $marca = trim($_POST['marca'] ?? '');
    $preco = str_replace(',', '.', $_POST['preco'] ?? 0);

    if ($nome === '' || $preco <= 0) {
        $erro = "Preencha os campos obrigatórios corretamente.";
    } else {

        $imagem = null;

        /* UPLOAD IMAGEM */
        if (!empty($_FILES['imagem']['name'])) {

            $permitidas = ['jpg','jpeg','png','webp'];
            $ext = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));

            if (in_array($ext, $permitidas)) {

                $imagem = uniqid() . '.' . $ext;

                move_uploaded_file(
                    $_FILES['imagem']['tmp_name'],
                    'uploads/' . $imagem
                );

            } else {
                $erro = "Formato de imagem inválido.";
            }
        }

        if (!$erro) {

            $stmt = $pdo->prepare("
                INSERT INTO products
                (nome, descricao, categoria, marca, preco, imagem)
                VALUES (?, ?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                $nome,
                $descricao,
                $categoria,
                $marca,
                $preco,
                $imagem
            ]);

            $msg = "Produto cadastrado com sucesso.";
        }
    }
}

require 'header.php';
?>

<style>
.product-page{
max-width:900px;
margin:auto;
}

.page-title{
font-size:34px;
font-weight:700;
color:#d43370;
margin-bottom:25px;
}

.form-box{
background:#fff;
padding:35px;
border-radius:22px;
box-shadow:0 10px 25px rgba(0,0,0,.08);
}

.form-grid{
display:grid;
grid-template-columns:1fr 1fr;
gap:20px;
}

.form-group{
display:flex;
flex-direction:column;
}

.form-group.full{
grid-column:1 / -1;
}

.form-group label{
font-weight:700;
margin-bottom:8px;
color:#444;
}

.form-group input,
.form-group textarea{
padding:14px;
border:1px solid #ddd;
border-radius:12px;
font-size:15px;
outline:none;
}

.form-group textarea{
min-height:140px;
resize:vertical;
}

.form-group input:focus,
.form-group textarea:focus{
border-color:#d43370;
}

.preview-box{
margin-top:10px;
font-size:13px;
color:#777;
}

.actions{
margin-top:25px;
display:flex;
gap:12px;
flex-wrap:wrap;
}

.btn{
cursor:pointer;
}

.btn-outline{
padding:10px 18px;
border-radius:10px;
font-weight:700;
}

.alert{
padding:14px;
border-radius:12px;
margin-bottom:18px;
font-weight:700;
}

.success-box{
background:#e9ffef;
color:#14813d;
}

.error-box{
background:#ffe9e9;
color:#b90000;
}

@media(max-width:900px){

.page-title{
font-size:26px;
}

.form-grid{
grid-template-columns:1fr;
}

.form-box{
padding:22px;
}

}
</style>

<div class="product-page">

<h2 class="page-title">Cadastrar Novo Produto</h2>

<div class="form-box">

<?php if($msg): ?>
<div class="alert success-box"><?= $msg ?></div>
<?php endif; ?>

<?php if($erro): ?>
<div class="alert error-box"><?= $erro ?></div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">

<div class="form-grid">

<div class="form-group">
<label>Nome *</label>
<input type="text" name="nome" required>
</div>

<div class="form-group">
<label>Preço *</label>
<input type="number" step="0.01" name="preco" required>
</div>

<div class="form-group">
<label>Categoria</label>
<input type="text" name="categoria">
</div>

<div class="form-group">
<label>Marca</label>
<input type="text" name="marca">
</div>

<div class="form-group full">
<label>Descrição</label>
<textarea name="descricao"></textarea>
</div>

<div class="form-group full">
<label>Imagem</label>
<input type="file" name="imagem" accept=".jpg,.jpeg,.png,.webp">
<div class="preview-box">
Formatos permitidos: JPG, PNG, WEBP
</div>
</div>

</div>

<div class="actions">
<button class="btn">Salvar Produto</button>
<a href="admin.php" class="btn-outline">Voltar</a>
</div>

</form>

</div>

</div>

<?php require 'footer.php'; ?>