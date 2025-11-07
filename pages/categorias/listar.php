<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorias - Sistema</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <?php
    require_once '../../config/database.php';
    require_once '../../config/session.php';
    
    try {
        $conn = getConnection();
        
        // Buscar categorias com contador de produtos
        $stmt = $conn->query("
            SELECT c.*, 
                   COUNT(p.id) as total_produtos
            FROM categorias c
            LEFT JOIN produtos p ON c.id = p.categoria_id AND p.ativo = 1
            WHERE c.ativa = 1
            GROUP BY c.id
            ORDER BY c.nome
        ");
        $categorias = $stmt->fetchAll();
        
    } catch (PDOException $e) {
        error_log("Erro ao listar categorias: " . $e->getMessage());
        $categorias = [];
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
                <li><a href="../produtos/listar.php">Produtos</a></li>
                <li><a href="listar.php">Categorias</a></li>
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
                <h1>📁 Categorias</h1>
                <p style="color: var(--gray-color);">
                    <?php echo count($categorias); ?> categoria(s) ativa(s)
                </p>
            </div>
            <?php if (canEdit()): ?>
            <a href="criar.php" class="btn btn-primary">+ Nova Categoria</a>
            <?php endif; ?>
        </div>

        <?php if (empty($categorias)): ?>
            <section style="text-align: center; padding: 3rem;">
                <p style="font-size: 3rem;">📁</p>
                <h2>Nenhuma categoria encontrada</h2>
                <?php if (canEdit()): ?>
                <p style="color: var(--gray-color);">
                    Comece criando a primeira categoria!
                </p>
                <a href="criar.php" class="btn btn-primary" style="margin-top: 1rem;">
                    + Criar Primeira Categoria
                </a>
                <?php endif; ?>
            </section>
        <?php else: ?>
            <section>
                <table>
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Descrição</th>
                            <th>Produtos</th>
                            <th>Data de Criação</th>
                            <?php if (canEdit()): ?>
                            <th style="text-align: center;">Ações</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categorias as $categoria): ?>
                        <tr>
                            <td>
                                <strong style="color: var(--primary-color);">
                                    <?php echo htmlspecialchars($categoria['nome']); ?>
                                </strong>
                            </td>
                            <td style="max-width: 300px;">
                                <?php 
                                $desc = $categoria['descricao'];
                                echo htmlspecialchars($desc ? (strlen($desc) > 80 ? substr($desc, 0, 80) . '...' : $desc) : '-'); 
                                ?>
                            </td>
                            <td>
                                <span class="badge <?php echo $categoria['total_produtos'] > 0 ? 'badge-success' : 'badge-warning'; ?>">
                                    <?php echo $categoria['total_produtos']; ?> produto(s)
                                </span>
                            </td>
                            <td>
                                <?php echo date('d/m/Y', strtotime($categoria['data_criacao'])); ?>
                            </td>
                            <?php if (canEdit()): ?>
                            <td>
                                <div class="table-actions" style="justify-content: center;">
                                    <a href="visualizar.php?id=<?php echo $categoria['id']; ?>" 
                                       class="btn btn-small btn-outline"
                                       title="Visualizar">
                                        👁️
                                    </a>
                                    <a href="editar.php?id=<?php echo $categoria['id']; ?>" 
                                       class="btn btn-small btn-warning"
                                       title="Editar">
                                        ✏️
                                    </a>
                                    <?php if ($categoria['total_produtos'] == 0): ?>
                                    <a href="deletar.php?id=<?php echo $categoria['id']; ?>" 
                                       class="btn btn-small btn-danger"
                                       onclick="return confirmarExclusao('<?php echo htmlspecialchars($categoria['nome']); ?>')"
                                       title="Deletar">
                                        🗑️
                                    </a>
                                    <?php else: ?>
                                    <button class="btn btn-small btn-danger" 
                                            disabled 
                                            title="Não é possível deletar categoria com produtos"
                                            style="opacity: 0.5; cursor: not-allowed;">
                                        🗑️
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <?php endif; ?>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; 2024 Sistema de Gerenciamento de Produtos</p>
    </footer>

    <script src="../../assets/js/script.js"></script>
</body>
</html>
