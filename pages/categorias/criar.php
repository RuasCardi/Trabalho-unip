<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Categoria - Sistema</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <?php
    require_once '../../config/database.php';
    require_once '../../config/session.php';
    
    // Protege - requer permissão de edição
    requireEdit();
    ?>
    
    <header>
        <nav>
            <a href="../../index.php" class="nav-brand">🛍️ Sistema de Produtos</a>
            <ul class="nav-menu">
                <li><a href="../../index.php">Início</a></li>
                <li><a href="../dashboard.php">Dashboard</a></li>
                <li><a href="../produtos/listar.php">Produtos</a></li>
                <li><a href="listar.php">Categorias</a></li>
            </ul>
            <div class="nav-user">
                <span class="user-badge"><?php echo htmlspecialchars(getUserName()); ?></span>
                <a href="../auth/logout.php" class="btn btn-small btn-outline">Sair</a>
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
        
        <section class="form-container">
            <h1>➕ Nova Categoria</h1>
            
            <form action="process_create.php" method="POST">
                <div class="form-group">
                    <label for="nome">Nome da Categoria *</label>
                    <input 
                        type="text" 
                        id="nome" 
                        name="nome" 
                        required 
                        minlength="3"
                        maxlength="100"
                        placeholder="Ex: Eletrônicos"
                    >
                </div>
                
                <div class="form-group">
                    <label for="descricao">Descrição</label>
                    <textarea 
                        id="descricao" 
                        name="descricao" 
                        rows="4"
                        maxlength="500"
                        placeholder="Descreva a categoria..."
                        onkeyup="contarCaracteres(this, 'desc-counter')"
                    ></textarea>
                    <small id="desc-counter" style="color: var(--gray-color);">0/500</small>
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="ativa" value="1" checked>
                        Categoria ativa
                    </label>
                </div>
                
                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">
                        ✔️ Cadastrar Categoria
                    </button>
                    <a href="listar.php" class="btn btn-outline">
                        ❌ Cancelar
                    </a>
                </div>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Sistema de Gerenciamento de Produtos</p>
    </footer>

    <script src="../../assets/js/script.js"></script>
</body>
</html>
