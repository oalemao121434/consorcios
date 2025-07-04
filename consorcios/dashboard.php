<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

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

// Consulta para obter mensagens de contato
$query_contatos = "SELECT * FROM contatos ORDER BY data_envio DESC LIMIT 5";
$result_contatos = $conn->query($query_contatos);

// Consulta para obter carros
$query_carros = "SELECT * FROM carros ORDER BY preco DESC";
$result_carros = $conn->query($query_carros);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - VELOZMOTORS</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f7fa;
            color: #333;
        }
        
        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar */
        .sidebar {
            width: 250px;
            background: linear-gradient(135deg, #1a1a1a 0%, #333 100%);
            color: white;
            padding: 20px 0;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }
        
        .logo {
            padding: 0 20px 20px;
            border-bottom: 1px solid #444;
            margin-bottom: 20px;
        }
        
        .logo h2 {
            color: #feca57;
            font-size: 1.5rem;
        }
        
        .nav-menu {
            list-style: none;
        }
        
        .nav-menu li a {
            display: block;
            padding: 12px 20px;
            color: #ddd;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .nav-menu li a:hover, .nav-menu li a.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            border-left: 3px solid #feca57;
        }
        
        /* Main Content */
        .main-content {
            flex: 1;
            padding: 20px;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .user-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .logout-btn {
            background: #ff6b6b;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        .logout-btn:hover {
            background: #e05555;
        }
        
        /* Cards */
        .cards-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        
        .card h3 {
            color: #666;
            font-size: 1rem;
            margin-bottom: 10px;
        }
        
        .card .value {
            font-size: 1.8rem;
            font-weight: 700;
            color: #1a1a1a;
        }
        
        .card .card-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }
        
        /* Tables */
        .table-container {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }
        
        .table-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .table-title h3 {
            color: #1a1a1a;
        }
        
        .btn {
            background: #feca57;
            color: #1a1a1a;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.9rem;
            transition: background 0.3s;
        }
        
        .btn:hover {
            background: #e6b84d;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        th {
            background-color: #f9f9f9;
            color: #666;
            font-weight: 500;
        }
        
        tr:hover {
            background-color: #f5f5f5;
        }
        
        .status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .status-pendente {
            background: #fff3e0;
            color: #ff9800;
        }
        
        .status-respondido {
            background: #e8f5e9;
            color: #4caf50;
        }
        
        .action-btn {
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 0.8rem;
            cursor: pointer;
            border: none;
            margin-right: 5px;
        }
        
        .edit-btn {
            background: #e3f2fd;
            color: #2196f3;
        }
        
        .delete-btn {
            background: #ffebee;
            color: #f44336;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">
                <h2>VELOZMOTORS</h2>
                <p>Painel Administrativo</p>
            </div>
            <ul class="nav-menu">
                <li><a href="dashboard.php" class="active">📊 Dashboard</a></li>
                <li><a href="#">🚗 Carros</a></li>
                <li><a href="#">✉️ Mensagens</a></li>
                <li><a href="#">👥 Clientes</a></li>
                <li><a href="#">📈 Relatórios</a></li>
                <li><a href="#">⚙️ Configurações</a></li>
            </ul>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h2>Bem-vindo, <?php echo $_SESSION['usuario']; ?></h2>
                <div class="user-info">
                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['usuario']); ?>&background=feca57&color=1a1a1a" alt="User">
                    <form action="logout.php" method="post">
                        <button type="submit" class="logout-btn">Sair</button>
                    </form>
                </div>
            </div>
            
            <!-- Cards Resumo -->
            <div class="cards-container">
                <div class="card">
                    <div class="card-icon">🚗</div>
                    <h3>Total de Carros</h3>
                    <div class="value"><?php echo $result_carros->num_rows; ?></div>
                </div>
                <div class="card">
                    <div class="card-icon">💰</div>
                    <h3>Valor em Estoque</h3>
                    <div class="value">R$ 15,2M</div>
                </div>
                <div class="card">
                    <div class="card-icon">✉️</div>
                    <h3>Novas Mensagens</h3>
                    <div class="value"><?php echo $result_contatos->num_rows; ?></div>
                </div>
                <div class="card">
                    <div class="card-icon">👥</div>
                    <h3>Clientes Ativos</h3>
                    <div class="value">42</div>
                </div>
            </div>
            
            <!-- Tabela de Mensagens Recentes -->
            <div class="table-container">
                <div class="table-title">
                    <h3>Mensagens Recentes</h3>
                    <a href="#" class="btn">Ver Todas</a>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Telefone</th>
                            <th>Assunto</th>
                            <th>Data</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($contato = $result_contatos->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($contato['nome']); ?></td>
                            <td><?php echo htmlspecialchars($contato['email']); ?></td>
                            <td><?php echo htmlspecialchars($contato['telefone']); ?></td>
                            <td><?php echo htmlspecialchars($contato['assunto']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($contato['data_envio'])); ?></td>
                            <td>
                                <span class="status status-<?php echo ($contato['respondido'] ? 'respondido' : 'pendente'); ?>">
                                    <?php echo ($contato['respondido'] ? 'Respondido' : 'Pendente'); ?>
                                </span>
                            </td>
                            <td>
                                <button class="action-btn edit-btn">Responder</button>
                                <button class="action-btn delete-btn">Excluir</button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Tabela de Carros -->
            <div class="table-container">
                <div class="table-title">
                    <h3>Nossos Carros</h3>
                    <a href="adicionar_carro.php" class="btn">Adicionar Carro</a>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Ano</th>
                            <th>Preço</th>
                            <th>Estoque</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($carro = $result_carros->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($carro['marca']); ?></td>
                            <td><?php echo htmlspecialchars($carro['modelo']); ?></td>
                            <td><?php echo htmlspecialchars($carro['ano']); ?></td>
                            <td>R$ <?php echo number_format($carro['preco'], 2, ',', '.'); ?></td>
                            <td><?php echo htmlspecialchars($carro['estoque']); ?></td>
                            <td>
                                <button class="action-btn edit-btn">Editar</button>
                                <button class="action-btn delete-btn">Excluir</button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>