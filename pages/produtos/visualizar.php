<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Produto - Sistema</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <?php
    require_once '../../config/database.php';
    require_once '../../config/session.php';
    
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    
    if (!$id) {
        setFlashMessage('Produto não encontrado.', 'error');
        header('Location: listar.php');
        exit;
    }
    
    try {
        $conn = getConnection();
        $stmt = $conn->prepare("
            SELECT p.*, c.nome as categoria_nome, u.nome as usuario_nome
            FROM produtos p
            JOIN categorias c ON p.categoria_id = c.id
            LEFT JOIN usuarios u ON p.usuario_criacao_id = u.id
            WHERE p.id = ? AND p.ativo = 1
        ");
        $stmt->execute([$id]);
        $produto = $stmt->fetch();
        
        if (!$produto) {
            setFlashMessage('Produto não encontrado.', 'error');
            header('Location: listar.php');
            exit;
        }
    } catch (PDOException $e) {
        error_log("Erro ao carregar produto: " . $e->getMessage());
        setFlashMessage('Erro ao carregar produto.', 'error');
        header('Location: listar.php');
        exit;
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
        
        <section>
            <div class="dashboard-header">
                <h1><?php echo htmlspecialchars($produto['nome']); ?></h1>
                <?php if (canEdit()): ?>
                <div class="btn-group">
                    <a href="editar.php?id=<?php echo $produto['id']; ?>" class="btn btn-warning">
                        ✏️ Editar
                    </a>
                    <a href="deletar.php?id=<?php echo $produto['id']; ?>" 
                       class="btn btn-danger"
                       onclick="return confirmarExclusao('<?php echo htmlspecialchars($produto['nome']); ?>')">
                        🗑️ Deletar
                    </a>
                </div>
                <?php endif; ?>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-top: 2rem;">
                <div>
                    <?php if ($produto['imagem'] && file_exists("../../uploads/produtos/" . $produto['imagem'])): ?>
                        <img src="../../uploads/produtos/<?php echo htmlspecialchars($produto['imagem']); ?>" 
                             alt="<?php echo htmlspecialchars($produto['nome']); ?>"
                             style="width: 100%; max-width: 500px; border-radius: 8px; box-shadow: var(--shadow-lg);">
                    <?php else: ?>
                        <div style="width: 100%; max-width: 500px; height: 400px; background: var(--light-color); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 5rem; color: var(--gray-color);">
                            📦
                        </div>
                    <?php endif; ?>
                </div>
                
                <div>
                    <div style="background: var(--light-color); padding: 1.5rem; border-radius: 8px; margin-bottom: 1rem;">
                        <h2 style="color: var(--primary-color); margin-bottom: 0.5rem;">
                            R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?>
                        </h2>
                        <span class="product-category">
                            <?php echo htmlspecialchars($produto['categoria_nome']); ?>
                        </span>
                    </div>
                    
                    <table style="margin-top: 1rem;">
                        <tr>
                            <th>Quantidade em Estoque</th>
                            <td>
                                <span class="<?php echo $produto['quantidade_estoque'] < 10 ? 'badge badge-danger' : 'badge badge-success'; ?>">
                                    <?php echo $produto['quantidade_estoque']; ?> unidades
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Categoria</th>
                            <td>
                                <a href="../categorias/visualizar.php?id=<?php echo $produto['categoria_id']; ?>"
                                   style="color: var(--primary-color);">
                                    <?php echo htmlspecialchars($produto['categoria_nome']); ?>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <th>Cadastrado por</th>
                            <td><?php echo htmlspecialchars($produto['usuario_nome'] ?? 'Sistema'); ?></td>
                        </tr>
                        <tr>
                            <th>Data de Criação</th>
                            <td><?php echo date('d/m/Y H:i', strtotime($produto['data_criacao'])); ?></td>
                        </tr>
                        <tr>
                            <th>Última Atualização</th>
                            <td><?php echo date('d/m/Y H:i', strtotime($produto['data_atualizacao'])); ?></td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <span class="badge badge-success">Ativo</span>
                            </td>
                        </tr>
                    </table>
                    
                    <div class="btn-group" style="margin-top: 2rem;">
                        <a href="listar.php" class="btn btn-outline">
                            ← Voltar para Lista
                        </a>
                        <?php if (canEdit()): ?>
                        <a href="editar.php?id=<?php echo $produto['id']; ?>" class="btn btn-primary">
                            Editar Produto
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <?php if ($produto['descricao']): ?>
            <div style="margin-top: 2rem; padding: 1.5rem; background: var(--light-color); border-radius: 8px;">
                <h3>📝 Descrição</h3>
                <p style="white-space: pre-wrap; color: var(--dark-color);">
                    <?php echo htmlspecialchars($produto['descricao']); ?>
                </p>
            </div>
            <?php endif; ?>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Sistema de Gerenciamento de Produtos</p>
    </footer>

    <script src="../../assets/js/script.js"></script>
</body>
</html>
