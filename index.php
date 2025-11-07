<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistema de Gerenciamento de Produtos">
    <title>Home - Sistema de Produtos</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php
    require_once 'config/database.php';
    require_once 'config/session.php';
    ?>
    
    <!-- Header semântico com navegação -->
    <header>
        <nav>
            <a href="index.php" class="nav-brand">🛍️ Sistema de Produtos</a>
            <ul class="nav-menu">
                <li><a href="index.php">Início</a></li>
                <li><a href="pages/produtos/listar.php">Produtos</a></li>
                <li><a href="pages/categorias/listar.php">Categorias</a></li>
                <?php if (isLoggedIn()): ?>
                    <li><a href="pages/dashboard.php">Dashboard</a></li>
                <?php endif; ?>
            </ul>
            <div class="nav-user">
                <?php if (isLoggedIn()): ?>
                    <span class="user-badge">
                        <?php echo htmlspecialchars(getUserName()); ?>
                        (<?php echo htmlspecialchars(getUserType()); ?>)
                    </span>
                    <a href="pages/auth/logout.php" class="btn btn-small btn-outline">Sair</a>
                <?php else: ?>
                    <a href="pages/auth/login.php" class="btn btn-small btn-primary">Login</a>
                    <a href="pages/auth/register.php" class="btn btn-small btn-outline">Registrar</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <!-- Conteúdo principal -->
    <main>
        <?php
        $flash = getFlashMessage();
        if ($flash):
        ?>
        <div class="alert alert-<?php echo htmlspecialchars($flash['type']); ?>">
            <?php echo htmlspecialchars($flash['message']); ?>
        </div>
        <?php endif; ?>
        
        <!-- Seção Hero -->
        <section style="text-align: center; padding: 4rem 2rem; background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)); color: white; border-radius: 8px; margin-bottom: 3rem;">
            <h1 style="color: white; border: none; font-size: 3rem; margin-bottom: 1rem;">
                Sistema de Gerenciamento de Produtos
            </h1>
            <p style="font-size: 1.25rem; margin-bottom: 2rem; color: rgba(255,255,255,0.9);">
                Solução completa para controle de estoque e categorias com segurança e integração
            </p>
            <?php if (!isLoggedIn()): ?>
            <div class="btn-group" style="justify-content: center;">
                <a href="pages/auth/register.php" class="btn btn-secondary" style="font-size: 1.1rem; padding: 1rem 2rem;">
                    Começar Agora
                </a>
                <a href="pages/auth/login.php" class="btn" style="background: white; color: var(--primary-color); font-size: 1.1rem; padding: 1rem 2rem;">
                    Fazer Login
                </a>
            </div>
            <?php else: ?>
            <a href="pages/dashboard.php" class="btn btn-secondary" style="font-size: 1.1rem; padding: 1rem 2rem;">
                Ir para Dashboard
            </a>
            <?php endif; ?>
        </section>

        <!-- Seção de Recursos -->
        <section>
            <h2 class="text-center">Recursos do Sistema</h2>
            <div class="stats-grid" style="margin-top: 2rem;">
                <article style="text-align: center;">
                    <div style="font-size: 3rem; color: var(--primary-color); margin-bottom: 1rem;">🔐</div>
                    <h3>Autenticação Segura</h3>
                    <p>Sistema de login com criptografia de senhas (bcrypt) e controle de permissões por tipo de usuário.</p>
                </article>
                
                <article style="text-align: center;">
                    <div style="font-size: 3rem; color: var(--secondary-color); margin-bottom: 1rem;">📦</div>
                    <h3>CRUD Completo</h3>
                    <p>Gerenciamento completo de produtos e categorias com prepared statements e proteção contra SQL Injection.</p>
                </article>
                
                <article style="text-align: center;">
                    <div style="font-size: 3rem; color: var(--warning-color); margin-bottom: 1rem;">🖼️</div>
                    <h3>Upload de Imagens</h3>
                    <p>Sistema de upload seguro com validação de tipo e tamanho para fotos de produtos.</p>
                </article>
                
                <article style="text-align: center;">
                    <div style="font-size: 3rem; color: var(--info-color); margin-bottom: 1rem;">🔍</div>
                    <h3>Busca Avançada</h3>
                    <p>Filtros por nome, categoria e faixa de preço para encontrar produtos rapidamente.</p>
                </article>
                
                <article style="text-align: center;">
                    <div style="font-size: 3rem; color: var(--danger-color); margin-bottom: 1rem;">🔗</div>
                    <h3>Integração POO2</h3>
                    <p>Banco de dados unificado compatível com sistema desktop em C# POO2.</p>
                </article>
                
                <article style="text-align: center;">
                    <div style="font-size: 3rem; color: var(--primary-color); margin-bottom: 1rem;">📱</div>
                    <h3>Design Responsivo</h3>
                    <p>Interface adaptável para desktop, tablet e smartphone com HTML semântico.</p>
                </article>
            </div>
        </section>

        <?php
        // Estatísticas públicas
        try {
            $conn = getConnection();
            
            $stmt = $conn->query("SELECT COUNT(*) as total FROM produtos WHERE ativo = 1");
            $total_produtos = $stmt->fetch()['total'];
            
            $stmt = $conn->query("SELECT COUNT(*) as total FROM categorias WHERE ativa = 1");
            $total_categorias = $stmt->fetch()['total'];
            
        ?>
        <!-- Estatísticas -->
        <section style="background: var(--primary-color); color: white; text-align: center;">
            <h2 style="color: white;">Números do Sistema</h2>
            <div class="stats-grid" style="margin-top: 2rem;">
                <div>
                    <div class="stat-value" style="color: white;"><?php echo $total_produtos; ?></div>
                    <div class="stat-label" style="color: rgba(255,255,255,0.8);">Produtos Cadastrados</div>
                </div>
                <div>
                    <div class="stat-value" style="color: white;"><?php echo $total_categorias; ?></div>
                    <div class="stat-label" style="color: rgba(255,255,255,0.8);">Categorias Ativas</div>
                </div>
                <div>
                    <div class="stat-value" style="color: white;">100%</div>
                    <div class="stat-label" style="color: rgba(255,255,255,0.8);">Segurança</div>
                </div>
            </div>
        </section>
        <?php
        } catch (PDOException $e) {
            error_log("Erro ao carregar estatísticas: " . $e->getMessage());
        }
        ?>

        <!-- Tecnologias -->
        <section>
            <h2 class="text-center">Tecnologias Utilizadas</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 2rem; margin-top: 2rem; text-align: center;">
                <div>
                    <strong style="color: var(--primary-color); font-size: 1.2rem;">PHP 7+</strong>
                    <p>Backend robusto</p>
                </div>
                <div>
                    <strong style="color: var(--secondary-color); font-size: 1.2rem;">MySQL</strong>
                    <p>Banco relacional</p>
                </div>
                <div>
                    <strong style="color: var(--warning-color); font-size: 1.2rem;">PDO</strong>
                    <p>Acesso seguro</p>
                </div>
                <div>
                    <strong style="color: var(--info-color); font-size: 1.2rem;">HTML5</strong>
                    <p>Semântico</p>
                </div>
                <div>
                    <strong style="color: var(--danger-color); font-size: 1.2rem;">CSS3</strong>
                    <p>Responsivo</p>
                </div>
                <div>
                    <strong style="color: var(--dark-color); font-size: 1.2rem;">JavaScript</strong>
                    <p>Interativo</p>
                </div>
            </div>
        </section>
    </main>

    <!-- Rodapé semântico -->
    <footer>
        <p>&copy; 2024 Sistema de Gerenciamento de Produtos</p>
        <p style="margin-top: 0.5rem; font-size: 0.9rem;">
            Desenvolvido para NP2 - Programação Web e POO2 | UNIP
        </p>
        <p style="margin-top: 0.5rem;">
            <a href="docs/README.md" style="color: white; text-decoration: underline;">
                Documentação
            </a>
        </p>
    </footer>

    <script src="assets/js/script.js"></script>
</body>
</html>
