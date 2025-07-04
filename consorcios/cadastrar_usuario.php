<?php
// Configurações do banco de dados
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'login';

// Conectar ao banco de dados
$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $novo_usuario = $_POST['usuario'] ?? '';
    $nova_senha = $_POST['senha'] ?? '';
    
    if (!empty($novo_usuario) && !empty($nova_senha)) {
        // Cria o hash da senha
        $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
        
        // Insere no banco de dados
        $stmt = $conn->prepare("INSERT INTO usuarios (usuario, senha) VALUES (?, ?)");
        $stmt->bind_param("ss", $novo_usuario, $senha_hash);
        
        if ($stmt->execute()) {
            $mensagem = "Usuário cadastrado com sucesso!";
        } else {
            $mensagem = "Erro ao cadastrar usuário: " . $conn->error;
        }
    } else {
        $mensagem = "Por favor, preencha todos os campos!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Novo Usuário</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 500px; margin: 0 auto; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input[type="text"], input[type="password"] {
            width: 100%; padding: 8px; margin-bottom: 10px;
        }
        button { padding: 10px 15px; background: #4CAF50; color: white; border: none; cursor: pointer; }
        .message { margin: 15px 0; padding: 10px; background: #f8f8f8; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Cadastrar Novo Usuário</h1>
        
        <?php if (!empty($mensagem)): ?>
            <div class="message"><?php echo $mensagem; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="usuario">Usuário:</label>
                <input type="text" id="usuario" name="usuario" required>
            </div>
            
            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required>
            </div>
            
            <button type="submit">Cadastrar</button>
        </form>
    </div>
</body>
</html>