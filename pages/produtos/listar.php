<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos - Sistema</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <?php
    require_once '../../config/database.php';
    require_once '../../config/session.php';
    
    // Busca e filtros
    $search = $_GET['search'] ?? '';
    $categoria_filter = $_GET['categoria'] ?? '';
    $preco_min = $_GET['preco_min'] ?? '';
    $preco_max = $_GET['preco_max'] ?? '';
    
    try {
        $conn = getConnection();
        
        // Buscar categorias para o filtro
        $stmt = $conn->query("SELECT id, nome FROM categorias WHERE ativa = 1 ORDER BY nome");
        $categorias = $stmt->fetchAll();
        
        // Construir query com filtros
        $query = "
            SELECT p.*, c.nome as categoria_nome, u.nome as usuario_nome
            FROM produtos p
            JOIN categorias c ON p.categoria_id = c.id
            LEFT JOIN usuarios u ON p.usuario_criacao_id = u.id
            WHERE p.ativo = 1
        ";
        
        $params = [];
        
        if ($search) {
            $query .= " AND (p.nome LIKE ? OR p.descricao LIKE ?)";
            $searchParam = "%$search%";
            $params[] = $searchParam;
            $params[] = $searchParam;
        }
        
        if ($categoria_filter) {
            $query .= " AND p.categoria_id = ?";
            $params[] = $categoria_filter;
        }
        
        if ($preco_min) {
            $query .= " AND p.preco >= ?";
            $params[] = $preco_min;
        }
        
        if ($preco_max) {
            $query .= " AND p.preco <= ?";
            $params[] = $preco_max;
        }
        
        $query .= " ORDER BY p.data_criacao DESC";
        
        $stmt = $conn->prepare($query);
        $stmt->execute($params);
        $produtos = $stmt->fetchAll();
        
    } catch (PDOException $e) {
        error_log("Erro ao listar produtos: " . $e->getMessage());
        $produtos = [];
    }
    ?>
    
    <header>
        <nav>
            <a href="../../index.php" class="nav-brand">🛍️ Sistema de Produtos</a>
            <ul class="nav-menu">
                <li><a href="../../index.php">Início</a></li>
                <?php if (isLoggedIn()): ?>
                <li><a href="../dashboard.php">Dashboard</a></li>
                <?php endif; ?>
                <li><a href="listar.php">Produtos</a></li>
                <li><a href="../categorias/listar.php">Categorias</a></li>
            </ul>
            <div class="nav-user">
                <?php if (isLoggedIn()): ?>
                    <span class="user-badge"><?php echo htmlspecialchars(getUserName()); ?></span>
                    <a href="../auth/logout.php" class="btn btn-small btn-outline">Sair</a>
                <?php else: ?>
                    <a href="../auth/login.php" class="btn btn-small btn-primary">Login</a>
                <?php endif; ?>
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
        
        <div class="dashboard-header">
            <div>
                <h1>📦 Produtos</h1>
                <p style="color: var(--gray-color);">
                    <?php echo count($produtos); ?> produto(s) encontrado(s)
                </p>
            </div>
            <?php if (canEdit()): ?>
            <a href="criar.php" class="btn btn-primary">+ Novo Produto</a>
            <?php endif; ?>
        </div>

        <!-- Filtros de busca -->
        <div class="filter-section">
            <form method="GET" class="filter-form">
                <div class="form-group">
                    <label for="search">🔍 Buscar</label>
                    <input 
                        type="text" 
                        id="search" 
                        name="search" 
                        placeholder="Nome ou descrição..."
                        value="<?php echo htmlspecialchars($search); ?>"
                    >
                </div>
                
                <div class="form-group">
                    <label for="categoria">📁 Categoria</label>
                    <select id="categoria" name="categoria">
                        <option value="">Todas</option>
                        <?php foreach ($categorias as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>" 
                                <?php echo ($categoria_filter == $cat['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat['nome']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="preco_min">💰 Preço Mínimo</label>
                    <input 
                        type="number" 
                        id="preco_min" 
                        name="preco_min" 
                        step="0.01"
                        placeholder="0.00"
                        value="<?php echo htmlspecialchars($preco_min); ?>"
                    >
                </div>
                
                <div class="form-group">
                    <label for="preco_max">💰 Preço Máximo</label>
                    <input 
                        type="number" 
                        id="preco_max" 
                        name="preco_max" 
                        step="0.01"
                        placeholder="9999.99"
                        value="<?php echo htmlspecialchars($preco_max); ?>"
                    >
                </div>
                
                <div class="form-group">
                    <label style="visibility: hidden;">Ações</label>
                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary">Filtrar</button>
                        <a href="listar.php" class="btn btn-outline">Limpar</a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Grid de produtos -->
        <?php if (empty($produtos)): ?>
            <section style="text-align: center; padding: 3rem;">
                <p style="font-size: 3rem;">📦</p>
                <h2>Nenhum produto encontrado</h2>
                <p style="color: var(--gray-color);">
                    <?php if ($search || $categoria_filter || $preco_min || $preco_max): ?>
                        Tente ajustar os filtros de busca.
                    <?php else: ?>
                        <?php if (canEdit()): ?>
                            Comece adicionando seu primeiro produto!
                        <?php else: ?>
                            Ainda não há produtos cadastrados.
                        <?php endif; ?>
                    <?php endif; ?>
                </p>
                <?php if (canEdit() && !$search && !$categoria_filter): ?>
                <a href="criar.php" class="btn btn-primary" style="margin-top: 1rem;">
                    + Adicionar Primeiro Produto
                </a>
                <?php endif; ?>
            </section>
        <?php else: ?>
            <div class="products-grid">
                <?php foreach ($produtos as $produto): ?>
                <article class="product-card" 
                         data-categoria="<?php echo $produto['categoria_id']; ?>"
                         data-preco="<?php echo $produto['preco']; ?>">
                    <?php if ($produto['imagem'] && file_exists("../../uploads/produtos/" . $produto['imagem'])): ?>
                        <img src="../../uploads/produtos/<?php echo htmlspecialchars($produto['imagem']); ?>" 
                             alt="<?php echo htmlspecialchars($produto['nome']); ?>"
                             class="product-image">
                    <?php else: ?>
                        <div class="product-image" style="display: flex; align-items: center; justify-content: center; font-size: 3rem; color: var(--gray-color);">
                            📦
                        </div>
                    <?php endif; ?>
                    
                    <div class="product-content">
                        <span class="product-category">
                            <?php echo htmlspecialchars($produto['categoria_nome']); ?>
                        </span>
                        
                        <h3 class="product-title">
                            <?php echo htmlspecialchars($produto['nome']); ?>
                        </h3>
                        
                        <p style="color: var(--gray-color); font-size: 0.9rem;">
                            <?php 
                            $descricao = $produto['descricao'];
                            echo htmlspecialchars(strlen($descricao) > 80 ? substr($descricao, 0, 80) . '...' : $descricao); 
                            ?>
                        </p>
                        
                        <div class="product-price">
                            R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?>
                        </div>
                        
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1rem;">
                            <span class="<?php echo $produto['quantidade_estoque'] < 10 ? 'badge badge-danger' : 'badge badge-success'; ?>">
                                Estoque: <?php echo $produto['quantidade_estoque']; ?>
                            </span>
                            
                            <a href="visualizar.php?id=<?php echo $produto['id']; ?>" 
                               class="btn btn-small btn-outline">
                                Ver detalhes
                            </a>
                        </div>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; 2024 Sistema de Gerenciamento de Produtos</p>
    </footer>

    <script src="../../assets/js/script.js"></script>
</body>
</html>
