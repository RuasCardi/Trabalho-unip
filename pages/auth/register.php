<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Registro de Usuário - Sistema de Produtos">
    <title>Registrar - Sistema de Produtos</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <!-- Header semântico -->
    <header>
        <nav>
            <a href="../../index.php" class="nav-brand">Sistema de Produtos</a>
        </nav>
    </header>

    <!-- Conteúdo principal -->
    <main>
        <section class="form-container">
            <h1>Criar Conta</h1>
            
            <?php
            // Incluir configurações
            require_once '../../config/database.php';
            require_once '../../config/session.php';
            
            // Se já estiver logado, redireciona para o dashboard
            if (isLoggedIn()) {
                header('Location: ../dashboard.php');
                exit;
            }
            
            // Exibir mensagens de feedback
            $flash = getFlashMessage();
            if ($flash):
            ?>
            <div class="alert alert-<?php echo htmlspecialchars($flash['type']); ?>">
                <?php echo htmlspecialchars($flash['message']); ?>
            </div>
            <?php endif; ?>
            
            <form action="process_register.php" method="POST" onsubmit="return validarFormularioRegistro(this)">
                <div class="form-group">
                    <label for="nome">Nome Completo</label>
                    <input 
                        type="text" 
                        id="nome" 
                        name="nome" 
                        required 
                        minlength="3"
                        maxlength="100"
                        placeholder="João da Silva"
                        autocomplete="name"
                    >
                </div>
                
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        required 
                        placeholder="seu@email.com"
                        autocomplete="email"
                    >
                </div>
                
                <div class="form-group">
                    <label for="senha">Senha</label>
                    <input 
                        type="password" 
                        id="senha" 
                        name="senha" 
                        required 
                        minlength="6"
                        placeholder="Mínimo 6 caracteres"
                        autocomplete="new-password"
                    >
                    <small style="color: var(--gray-color);">
                        A senha deve ter pelo menos 6 caracteres
                    </small>
                </div>
                
                <div class="form-group">
                    <label for="confirmar_senha">Confirmar Senha</label>
                    <input 
                        type="password" 
                        id="confirmar_senha" 
                        name="confirmar_senha" 
                        required 
                        minlength="6"
                        placeholder="Digite a senha novamente"
                        autocomplete="new-password"
                    >
                </div>
                
                <div class="form-group">
                    <label for="tipo_usuario">Tipo de Usuário</label>
                    <select id="tipo_usuario" name="tipo_usuario" required>
                        <option value="visualizador">Visualizador (apenas leitura)</option>
                        <option value="editor" selected>Editor (pode adicionar/editar)</option>
                    </select>
                    <small style="color: var(--gray-color);">
                        Administradores devem ser criados por outro admin
                    </small>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        Criar Conta
                    </button>
                </div>
            </form>
            
            <p class="text-center mt-2">
                Já tem uma conta? 
                <a href="login.php" style="color: var(--primary-color); font-weight: 600;">
                    Faça login aqui
                </a>
            </p>
        </section>
    </main>

    <!-- Rodapé semântico -->
    <footer>
        <p>&copy; 2024 Sistema de Gerenciamento de Produtos. Desenvolvido para NP2.</p>
    </footer>

    <script src="../../assets/js/script.js"></script>
</body>
</html>
