

<?php

require 'config.php';
require 'header.php';

$enviado = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $enviado = true;
}

?>

<section class="contact-page">

    <div class="contact-top">
        <span class="contact-badge">Fale Conosco</span>

        <h1>Entre em contato com a Le Petit Papelaria</h1>

        <p>
            Tire dúvidas, solicite informações sobre pedidos,
            produtos ou parcerias.
        </p>
    </div>

    <div class="contact-grid">

        <!-- FORM -->
        <div class="contact-box">

            <h2>Enviar Mensagem</h2>

            <?php if ($enviado): ?>
                <div class="success">
                    Mensagem enviada com sucesso.
                </div>
            <?php endif; ?>

            <form method="post">

                <label>Nome</label>
                <input type="text" name="nome" required>

                <label>E-mail</label>
                <input type="email" name="email" required>

                <label>Telefone</label>
                <input type="text" name="telefone">

                <label>Mensagem</label>
                <textarea name="mensagem" required></textarea>

                <button class="btn full">
                    Enviar Mensagem
                </button>

            </form>

        </div>

        <!-- INFO -->
        <div class="contact-box">

            <h2>Informações</h2>

            <div class="contact-item">
                <strong>WhatsApp</strong>
                <span>(44) 99999-9999</span>
            </div>

            <div class="contact-item">
                <strong>E-mail</strong>
                <span>contato@lepetit.com.br</span>
            </div>

            <div class="contact-item">
                <strong>Instagram</strong>
                <span>@lepetitpapelaria</span>
            </div>

            <div class="contact-item">
                <strong>Horário</strong>
                <span>Seg a Sex • 08h às 18h</span>
            </div>

            <div class="contact-item">
                <strong>Localização</strong>
                <span>Campo Mourão - PR</span>
            </div>

        </div>

    </div>

</section>

<?php require 'footer.php'; ?>