<?php
session_start();

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

// Se o usuário já está logado, redireciona para o dashboard
if (isset($_SESSION['id'])) {
    header("Location: dashboard.php");
    exit();
}

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (!empty($dados["Sendlogin"])) {
    $query_usuario = "SELECT id, usuario, senha FROM usuarios WHERE usuario = ? LIMIT 1";
    $stmt = $conn->prepare($query_usuario);
    $stmt->bind_param("s", $dados["usuario"]);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado->num_rows == 1) {
        $row_usuario = $resultado->fetch_assoc();
        
        // Verifica a senha usando password_verify()
        if (password_verify($dados["senha_usuario"], $row_usuario['senha'])) {
            $_SESSION['id'] = $row_usuario['id'];
            $_SESSION['usuario'] = $row_usuario['usuario'];
            
            header("Location: dashboard.php");
            exit();
        } else {
            $erro = "Senha incorreta!";
        }
    } else {
        $erro = "Usuário não encontrado!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - VELOZMOTORS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 350px;
        }
        .login-container h2 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        .btn-login {
            width: 100%;
            padding: 10px;
            background-color: #feca57;
            border: none;
            border-radius: 4px;
            color: #1a1a1a;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn-login:hover {
            background-color: #e6b84d;
        }
        .error-message {
            color: #ff6b6b;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Área Restrita</h2>
        
        <?php if (!empty($erro)): ?>
            <div class="error-message"><?php echo $erro; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="usuario">Usuário:</label>
                <input type="text" id="usuario" name="usuario" placeholder="Digite seu usuário" required>
            </div>
            
            <div class="form-group">
                <label for="senha_usuario">Senha:</label>
                <input type="password" id="senha_usuario" name="senha_usuario" placeholder="Digite sua senha" required>
            </div>
            
            <button type="submit" name="Sendlogin" class="btn-login">Acessar</button>
        </form>
    </div>
</body>
</html>