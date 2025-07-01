<?php
require_once '../includes/auth.php';

$pageTitle = "Dashboard";
include '../includes/header.php';
?>

<div class="dashboard">
    <aside class="sidebar">
        <h2>Menu</h2>
        <nav>
            <ul>
                <li><a href="propriedades/listar.php">Propriedades</a></li>
                <li><a href="mensagens/listar.php">Mensagens</a></li>
                <li><a href="../logout.php">Sair</a></li>
            </ul>
        </nav>
    </aside>
    
    <main class="content">
        <h1>Bem-vindo, <?php echo $_SESSION['usuario']; ?>!</h1>
        
        <div class="stats">
            <div class="stat-card">
                <h3>Propriedades</h3>
                <p>15</p>
            </div>
            <div class="stat-card">
                <h3>Mensagens</h3>
                <p>8</p>
            </div>
            <div class="stat-card">
                <h3>Visitas</h3>
                <p>1,243</p>
            </div>
        </div>
    </main>
</div>

<?php include '../includes/footer.php'; ?>