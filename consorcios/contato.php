<?php
require_once 'classes/Database.php';
require_once 'classes/Mensagem.php';

$mensagem = new Mensagem();
$sucesso = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $mensagem_texto = $_POST['mensagem'];
    
    if ($mensagem->cadastrar($nome, $email, $telefone, $mensagem_texto)) {
        $sucesso = true;
    }
}

$pageTitle = "Contato";
include 'includes/header.php';
?>

<section class="contact">
    <h1>Entre em Contato</h1>
    
    <?php if ($sucesso): ?>
        <div class="alert alert-success">
            Mensagem enviada com sucesso! Responderemos em breve.
        </div>
    <?php endif; ?>
    
    <form method="POST" action="">
        <div class="form-group">
            <label>Nome:</label>
            <input type="text" name="nome" required>
        </div>
        <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" required>
        </div>
        <div class="form-group">
            <label>Telefone:</label>
            <input type="tel" name="telefone">
        </div>
        <div class="form-group">
            <label>Mensagem:</label>
            <textarea name="mensagem" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn">Enviar Mensagem</button>
    </form>
</section>

<?php include 'includes/footer.php'; ?>