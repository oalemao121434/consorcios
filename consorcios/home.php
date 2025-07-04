<?php
// Iniciar sessão (para possível integração com login)
session_start();

// Conexão com o banco de dados para carros em destaque
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'login';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Buscar os 4 carros mais caros para exibir na vitrine
$query_carros = "SELECT * FROM carros ORDER BY preco DESC LIMIT 4";
$result_carros = $conn->query($query_carros);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VELOZMOTORS - Carros de Luxo</title>
    <style>
        /* Reset e Estilos Globais */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }
        
        body {
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        /* Cabeçalho */
        header {
            background: linear-gradient(135deg, #1a1a1a 0%, #333 100%);
            color: white;
            padding: 20px 0;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .logo h1 {
            font-size: 2.2rem;
            font-weight: 700;
            letter-spacing: 2px;
            background: linear-gradient(to right, #ff6b6b, #feca57);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .nav-links {
            display: flex;
            gap: 30px;
        }
        
        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }
        
        .nav-links a:hover {
            color: #feca57;
        }
        
        /* Seção Hero */
        .hero {
            height: 100vh;
            background: url('https://images.unsplash.com/photo-1494972308805-463bc619d34e?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80') no-repeat center center/cover;
            display: flex;
            align-items: center;
            position: relative;
            margin-top: 80px;
        }
        
        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
        }
        
        .hero-content {
            position: relative;
            z-index: 1;
            color: white;
            max-width: 600px;
        }
        
        .hero-content h2 {
            font-size: 3rem;
            margin-bottom: 20px;
            line-height: 1.2;
        }
        
        .hero-content p {
            font-size: 1.2rem;
            margin-bottom: 30px;
        }
        
        .btn {
            display: inline-block;
            background: #feca57;
            color: #1a1a1a;
            padding: 12px 30px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn:hover {
            background: #ff6b6b;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        /* Sobre a Empresa */
        .about {
            padding: 80px 0;
            background-color: white;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 50px;
            font-size: 2.5rem;
            color: #1a1a1a;
        }
        
        .about-content {
            display: flex;
            gap: 50px;
            align-items: center;
        }
        
        .about-text {
            flex: 1;
        }
        
        .about-text h3 {
            font-size: 1.8rem;
            margin-bottom: 20px;
            color: #333;
        }
        
        .about-text p {
            margin-bottom: 15px;
            color: #666;
        }
        
        .about-image {
            flex: 1;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        .about-image img {
            width: 100%;
            height: auto;
            display: block;
        }
        
        /* Vitrine de Carros */
        .showroom {
            padding: 80px 0;
            background-color: #f9f9f9;
        }
        
        .cars-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
        }
        
        .car-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .car-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }
        
        .car-image {
            height: 200px;
            overflow: hidden;
        }
        
        .car-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }
        
        .car-card:hover .car-image img {
            transform: scale(1.1);
        }
        
        .car-info {
            padding: 20px;
        }
        
        .car-info h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: #1a1a1a;
        }
        
        .car-specs {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
            color: #666;
        }
        
        .car-specs span {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .car-price {
            font-size: 1.3rem;
            font-weight: 700;
            color: #feca57;
            margin-bottom: 15px;
        }
        
        /* Formulário de Contato */
        .contact {
            padding: 80px 0;
            background-color: white;
        }
        
        .contact-form {
            max-width: 600px;
            margin: 0 auto;
            background: #f9f9f9;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }
        
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        
        .form-group textarea {
            height: 150px;
        }
        
        /* Rodapé */
        footer {
            background: #1a1a1a;
            color: white;
            padding: 50px 0 20px;
        }
        
        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
        }
        
        .footer-column h4 {
            font-size: 1.2rem;
            margin-bottom: 20px;
            color: #feca57;
        }
        
        .footer-column ul {
            list-style: none;
        }
        
        .footer-column ul li {
            margin-bottom: 10px;
        }
        
        .footer-column ul li a {
            color: #ccc;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .footer-column ul li a:hover {
            color: #feca57;
        }
        
        .social-links {
            display: flex;
            gap: 15px;
        }
        
        .social-links a {
            color: white;
            font-size: 1.5rem;
            transition: color 0.3s;
        }
        
        .social-links a:hover {
            color: #feca57;
        }
        
        .copyright {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #333;
            color: #999;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <!-- Cabeçalho -->
    <header>
        <div class="container">
            <nav>
                <div class="logo">
                    <h1>VELOZMOTORS</h1>
                </div>
                <div class="nav-links">
                    <a href="#home">Home</a>
                    <a href="#showroom">Carros</a>
                    <a href="#about">Sobre</a>
                    <a href="#contact">Contato</a>
                    <a href="index.php">Área Restrita</a>
                </div>
            </nav>
        </div>
    </header>

    <!-- Seção Hero -->
    <section class="hero" id="home">
        <div class="container">
            <div class="hero-content">
                <h2>Excelência em Automóveis de Luxo</h2>
                <p>Na VELOZMOTORS, trazemos os veículos mais exclusivos e sofisticados do mercado para você.</p>
                <a href="#showroom" class="btn">Conheça Nossa Coleção</a>
            </div>
        </div>
    </section>

    <!-- Sobre a Empresa -->
    <section class="about" id="about">
        <div class="container">
            <h2 class="section-title">Sobre a VELOZMOTORS</h2>
            <div class="about-content">
                <div class="about-text">
                    <h3>Nossa História</h3>
                    <p>Fundada em 2010, a VELOZMOTORS rapidamente se estabeleceu como líder no mercado de carros de luxo, oferecendo veículos exclusivos e um serviço personalizado incomparável.</p>
                    <p>Nossa missão é proporcionar uma experiência única de compra, conectando clientes exigentes aos melhores automóveis do mundo.</p>
                    <p>Com showrooms em 5 países e uma equipe de especialistas apaixonados por carros, garantimos que cada cliente encontre exatamente o que procura.</p>
                    <a href="#contact" class="btn">Fale Conosco</a>
                </div>
                <div class="about-image">
                    <img src="https://images.unsplash.com/photo-1601362840469-51e4d8d58785?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" alt="Showroom VELOZMOTORS">
                </div>
            </div>
        </div>
    </section>

    <!-- Vitrine de Carros -->
    <section class="showroom" id="showroom">
        <div class="container">
            <h2 class="section-title">Nossos Destaques</h2>
            <div class="cars-grid">
                <?php while($carro = $result_carros->fetch_assoc()): ?>
                <div class="car-card">
                    <div class="car-image">
                        <img src="<?php echo $carro['imagem'] ?: 'https://via.placeholder.com/300x200?text=Carro+Sem+Imagem'; ?>" alt="<?php echo htmlspecialchars($carro['marca'].' '.htmlspecialchars($carro['modelo'])); ?>">
                    </div>
                    <div class="car-info">
                        <h3><?php echo htmlspecialchars($carro['marca'].' '.htmlspecialchars($carro['modelo'])); ?></h3>
                        <div class="car-specs">
                            <span>📅 <?php echo htmlspecialchars($carro['ano']); ?></span>
                            <span>💰 R$ <?php echo number_format($carro['preco'], 2, ',', '.'); ?></span>
                        </div>
                        <p><?php echo htmlspecialchars($carro['descricao']); ?></p>
                        <a href="#contact" class="btn">Solicitar Detalhes</a>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <!-- Formulário de Contato -->
    <section class="contact" id="contact">
        <div class="container">
            <h2 class="section-title">Entre em Contato</h2>
            <div class="contact-form">
                <form action="processa_contato.php" method="POST">
                    <div class="form-group">
                        <label for="nome">Nome Completo:</label>
                        <input type="text" id="nome" name="nome" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">E-mail:</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="telefone">Telefone:</label>
                        <input type="tel" id="telefone" name="telefone">
                    </div>
                    
                    <div class="form-group">
                        <label for="assunto">Assunto:</label>
                        <select id="assunto" name="assunto" required>
                            <option value="">Selecione...</option>
                            <option value="consulta">Consulta sobre veículo</option>
                            <option value="financiamento">Financiamento</option>
                            <option value="agendamento">Agendamento de test drive</option>
                            <option value="outro">Outro assunto</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="mensagem">Mensagem:</label>
                        <textarea id="mensagem" name="mensagem" required></textarea>
                    </div>
                    
                    <button type="submit" class="btn">Enviar Mensagem</button>
                </form>
            </div>
        </div>
    </section>

    <!-- Rodapé -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h4>VELOZMOTORS</h4>
                    <p>Excelência em automóveis de luxo desde 2010. Oferecemos os veículos mais exclusivos do mercado com serviço personalizado.</p>
                    <div class="social-links">
                        <a href="#">📱</a>
                        <a href="#">📘</a>
                        <a href="#">📸</a>
                        <a href="#">🔗</a>
                    </div>
                </div>
                <div class="footer-column">
                    <h4>Links Rápidos</h4>
                    <ul>
                        <li><a href="#home">Home</a></li>
                        <li><a href="#showroom">Carros</a></li>
                        <li><a href="#about">Sobre Nós</a></li>
                        <li><a href="#contact">Contato</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h4>Contato</h4>
                    <ul>
                        <li>📞 (11) 4002-8922</li>
                        <li>✉️ contato@velozmotors.com</li>
                        <li>📍 Av. Paulista, 1000 - São Paulo/SP</li>
                        <li>⏰ Seg-Sex: 9h-18h | Sáb: 9h-13h</li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h4>Newsletter</h4>
                    <p>Assine para receber novidades e ofertas exclusivas.</p>
                    <form action="processa_newsletter.php" method="POST">
                        <input type="email" name="email" placeholder="Seu e-mail" required>
                        <button type="submit" class="btn">Assinar</button>
                    </form>
                </div>
            </div>
            <div class="copyright">
                <p>&copy; <?php echo date('Y'); ?> VELOZMOTORS. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>
</body>
</html>