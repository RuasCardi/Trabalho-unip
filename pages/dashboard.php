<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Dashboard - Sistema de Produtos">
    <title>Dashboard - Sistema de Produtos</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php
    require_once '../config/database.php';
    require_once '../config/session.php';
    
    // Protege a página - requer login
    requireLogin();
    
    try {
        $conn = getConnection();
        
        // Estatísticas gerais
        $stmt = $conn->query("SELECT COUNT(*) as total FROM produtos WHERE ativo = 1");
        $total_produtos = $stmt->fetch()['total'];
        
        $stmt = $conn->query("SELECT COUNT(*) as total FROM categorias WHERE ativa = 1");
        $total_categorias = $stmt->fetch()['total'];
        
        $stmt = $conn->query("SELECT COUNT(*) as total FROM usuarios WHERE ativo = 1");
        $total_usuarios = $stmt->fetch()['total'];
        
        $stmt = $conn->query("SELECT SUM(preco * quantidade_estoque) as total FROM produtos WHERE ativo = 1");
        $valor_estoque = $stmt->fetch()['total'] ?? 0;
        
        // Produtos com estoque baixo
        $stmt = $conn->query("
            SELECT p.*, c.nome as categoria_nome 
            FROM produtos p
            JOIN categorias c ON p.categoria_id = c.id
            WHERE p.quantidade_estoque < 10 AND p.ativo = 1
            ORDER BY p.quantidade_estoque ASC
            LIMIT 5
        ");
        $produtos_baixo_estoque = $stmt->fetchAll();
        
        // Últimos produtos cadastrados
        $stmt = $conn->query("
            SELECT p.*, c.nome as categoria_nome 
            FROM produtos p
            JOIN categorias c ON p.categoria_id = c.id
            WHERE p.ativo = 1
            ORDER BY p.data_criacao DESC
            LIMIT 5
        ");
        $ultimos_produtos = $stmt->fetchAll();
        
    } catch (PDOException $e) {
        error_log("Erro no dashboard: " . $e->getMessage());
        setFlashMessage('Erro ao carregar dados do dashboard.', 'error');
    }
    ?>
    
    <!-- Header com navegação -->
    <header>
        <nav>
            <a href="../index.php" class="nav-brand">🛍️ Sistema de Produtos</a>
            <ul class="nav-menu">
                <li><a href="../index.php">Início</a></li>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="produtos/listar.php">Produtos</a></li>
                <li><a href="categorias/listar.php">Categorias</a></li>
                <?php if (isAdmin()): ?>
                <li><a href="usuarios/listar.php">Usuários</a></li>
                <?php endif; ?>
            </ul>
            <div class="nav-user">
                <span class="user-badge">
                    <?php echo htmlspecialchars(getUserName()); ?>
                    (<?php echo htmlspecialchars(getUserType()); ?>)
                </span>
                <a href="auth/logout.php" class="btn btn-small btn-outline">Sair</a>
            </div>
        </nav>
    </header>

    <main>
        <?php
        $flash = getFlashMessage();
        if ($flash):
        ?>
        <div class="alert alert-<?php echo htmlspecialchars($flash['type']); ?>">
            <?php echo htmlspecialchars($flash['message']); ?>
        </div>
        <?php endif; ?>
        
        <!-- Header do Dashboard -->
        <div class="dashboard-header">
            <div>
                <h1>Dashboard</h1>
                <p style="color: var(--gray-color);">
                    Bem-vindo de volta, <?php echo htmlspecialchars(getUserName()); ?>!
                </p>
            </div>
            <?php if (canEdit()): ?>
            <div class="btn-group">
                <a href="produtos/criar.php" class="btn btn-primary">+ Novo Produto</a>
                <a href="categorias/criar.php" class="btn btn-secondary">+ Nova Categoria</a>
            </div>
            <?php endif; ?>
        </div>

        <!-- Estatísticas -->
        <div class="stats-grid">
            <div class="stat-card" style="border-left-color: var(--primary-color);">
                <div class="stat-value"><?php echo $total_produtos; ?></div>
                <div class="stat-label">Produtos Ativos</div>
                <a href="produtos/listar.php" style="color: var(--primary-color); font-size: 0.9rem;">Ver todos →</a>
            </div>
            
            <div class="stat-card" style="border-left-color: var(--secondary-color);">
                <div class="stat-value" style="color: var(--secondary-color);"><?php echo $total_categorias; ?></div>
                <div class="stat-label">Categorias Ativas</div>
                <a href="categorias/listar.php" style="color: var(--secondary-color); font-size: 0.9rem;">Ver todas →</a>
            </div>
            
            <div class="stat-card" style="border-left-color: var(--warning-color);">
                <div class="stat-value" style="color: var(--warning-color);">
                    R$ <?php echo number_format($valor_estoque, 2, ',', '.'); ?>
                </div>
                <div class="stat-label">Valor em Estoque</div>
            </div>
            
            <?php if (isAdmin()): ?>
            <div class="stat-card" style="border-left-color: var(--info-color);">
                <div class="stat-value" style="color: var(--info-color);"><?php echo $total_usuarios; ?></div>
                <div class="stat-label">Usuários Ativos</div>
                <a href="usuarios/listar.php" style="color: var(--info-color); font-size: 0.9rem;">Gerenciar →</a>
            </div>
            <?php endif; ?>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 2rem;">
            <!-- Produtos com estoque baixo -->
            <section>
                <h2 style="color: var(--danger-color);">⚠️ Estoque Baixo</h2>
                <?php if (empty($produtos_baixo_estoque)): ?>
                    <p style="color: var(--gray-color);">Nenhum produto com estoque baixo.</p>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Produto</th>
                                <th>Categoria</th>
                                <th>Estoque</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($produtos_baixo_estoque as $produto): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($produto['nome']); ?></td>
                                <td>
                                    <span class="product-category">
                                        <?php echo htmlspecialchars($produto['categoria_nome']); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-danger">
                                        <?php echo $produto['quantidade_estoque']; ?> un.
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </section>

            <!-- Últimos produtos -->
            <section>
                <h2 style="color: var(--secondary-color);">🆕 Produtos Recentes</h2>
                <?php if (empty($ultimos_produtos)): ?>
                    <p style="color: var(--gray-color);">Nenhum produto cadastrado.</p>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Produto</th>
                                <th>Preço</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($ultimos_produtos as $produto): ?>
                            <tr>
                                <td>
                                    <strong><?php echo htmlspecialchars($produto['nome']); ?></strong><br>
                                    <small style="color: var(--gray-color);">
                                        <?php echo htmlspecialchars($produto['categoria_nome']); ?>
                                    </small>
                                </td>
                                <td>
                                    <strong style="color: var(--primary-color);">
                                        R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?>
                                    </strong>
                                </td>
                                <td>
                                    <a href="produtos/visualizar.php?id=<?php echo $produto['id']; ?>" 
                                       class="btn btn-small btn-outline">
                                        Ver
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </section>
        </div>

        <!-- Ações rápidas -->
        <section style="background: var(--light-color); text-align: center;">
            <h2>Ações Rápidas</h2>
            <div class="btn-group" style="justify-content: center; margin-top: 1.5rem;">
                <a href="produtos/listar.php" class="btn btn-outline">📦 Ver Produtos</a>
                <a href="categorias/listar.php" class="btn btn-outline">📁 Ver Categorias</a>
                <?php if (canEdit()): ?>
                <a href="produtos/criar.php" class="btn btn-primary">+ Adicionar Produto</a>
                <?php endif; ?>
                <?php if (isAdmin()): ?>
                <a href="usuarios/listar.php" class="btn btn-secondary">👥 Gerenciar Usuários</a>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Sistema de Gerenciamento de Produtos. Desenvolvido para NP2.</p>
    </footer>

    <script src="../assets/js/script.js"></script>
</body>
</html>
