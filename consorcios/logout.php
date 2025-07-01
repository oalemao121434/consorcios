<?php
session_start();

if (isset($_SESSION['usuario'])) {
    header("Location: admin/dashboard.php");
    exit();
}

require_once 'classes/Database.php';
require_once 'classes/Usuario.php';

$usuario = new Usuario();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['usuario'];
    $password = $_POST['senha'];
    
    if ($usuario->login($username, $password)) {
        $_SESSION['usuario'] = $username;
        header("Location: admin/dashboard.php");
        exit();
    } else {
        $erro = "Usuário ou senha incorretos!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Specter Homes</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="login-container">
        <h1>Login Administrativo</h1>
        <?php if (isset($erro)): ?>
            <div class="alert alert-danger"><?php echo $erro; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label>Usuário:</label>
                <input type="text" name="usuario" required>
            </div>
            <div class="form-group">
                <label>Senha:</label>
                <input type="password" name="senha" required>
            </div>
            <button type="submit" class="btn">Acessar</button>
        </form>
    </div>
</body>
</html>