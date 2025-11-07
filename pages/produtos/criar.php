<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Produto - Sistema</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <?php
    require_once '../../config/database.php';
    require_once '../../config/session.php';
    
    // Protege - requer permissão de edição
    requireEdit();
    
    try {
        $conn = getConnection();
        $stmt = $conn->query("SELECT id, nome FROM categorias WHERE ativa = 1 ORDER BY nome");
        $categorias = $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Erro ao carregar categorias: " . $e->getMessage());
        $categorias = [];
    }
    ?>
    
    <header>
        <nav>
            <a href="../../index.php" class="nav-brand">🛍️ Sistema de Produtos</a>
            <ul class="nav-menu">
                <li><a href="../../index.php">Início</a></li>
                <li><a href="../dashboard.php">Dashboard</a></li>
                <li><a href="listar.php">Produtos</a></li>
                <li><a href="../categorias/listar.php">Categorias</a></li>
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
        
        <section class="form-container" style="max-width: 800px;">
            <h1>➕ Novo Produto</h1>
            
            <form action="process_create.php" method="POST" enctype="multipart/form-data" onsubmit="return validarFormularioProduto(this)">
                <div class="form-group">
                    <label for="nome">Nome do Produto *</label>
                    <input 
                        type="text" 
                        id="nome" 
                        name="nome" 
                        required 
                        minlength="3"
                        maxlength="200"
                        placeholder="Ex: Notebook Dell Inspiron"
                    >
                </div>
                
                <div class="form-group">
                    <label for="descricao">Descrição</label>
                    <textarea 
                        id="descricao" 
                        name="descricao" 
                        rows="4"
                        maxlength="1000"
                        placeholder="Descreva as características do produto..."
                        onkeyup="contarCaracteres(this, 'desc-counter')"
                    ></textarea>
                    <small id="desc-counter" style="color: var(--gray-color);">0/1000</small>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label for="preco">Preço (R$) *</label>
                        <input 
                            type="number" 
                            id="preco" 
                            name="preco" 
                            required 
                            min="0.01"
                            step="0.01"
                            placeholder="0.00"
                            onkeyup="atualizarPreviewPreco(this)"
                        >
                        <small id="preco-preview" style="color: var(--primary-color); font-weight: 600;"></small>
                    </div>
                    
                    <div class="form-group">
                        <label for="quantidade_estoque">Quantidade em Estoque *</label>
                        <input 
                            type="number" 
                            id="quantidade_estoque" 
                            name="quantidade_estoque" 
                            required 
                            min="0"
                            value="0"
                            placeholder="0"
                        >
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="categoria_id">Categoria *</label>
                    <select id="categoria_id" name="categoria_id" required>
                        <option value="">Selecione uma categoria</option>
                        <?php foreach ($categorias as $categoria): ?>
                        <option value="<?php echo $categoria['id']; ?>">
                            <?php echo htmlspecialchars($categoria['nome']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (empty($categorias)): ?>
                    <small style="color: var(--danger-color);">
                        ⚠️ Nenhuma categoria disponível. 
                        <a href="../categorias/criar.php" style="color: var(--primary-color);">Criar categoria</a>
                    </small>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="imagem">📷 Imagem do Produto (opcional)</label>
                    <input 
                        type="file" 
                        id="imagem" 
                        name="imagem" 
                        accept="image/jpeg,image/jpg,image/png,image/gif,image/webp"
                        onchange="validarImagem(this)"
                    >
                    <small style="color: var(--gray-color);">
                        Formatos aceitos: JPG, PNG, GIF, WEBP | Tamanho máximo: 5MB
                    </small>
                    <img id="image-preview" src="#" alt="Preview" 
                         style="display: none; max-width: 300px; margin-top: 1rem; border-radius: 8px;">
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="ativo" value="1" checked>
                        Produto ativo (visível no sistema)
                    </label>
                </div>
                
                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">
                        ✔️ Cadastrar Produto
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
