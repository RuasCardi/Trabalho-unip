<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistema de Login - Gerenciamento de Produtos">
    <title>Login - Sistema de Produtos</title>
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
            <h1>Login</h1>
            
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
            
            <form action="process_login.php" method="POST" onsubmit="return validarFormularioLogin(this)">
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
                        placeholder="Digite sua senha"
                        minlength="6"
                        autocomplete="current-password"
                    >
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        Entrar
                    </button>
                </div>
            </form>
            
            <p class="text-center mt-2">
                Não tem uma conta? 
                <a href="register.php" style="color: var(--primary-color); font-weight: 600;">
                    Registre-se aqui
                </a>
            </p>
            
            <div style="margin-top: 2rem; padding: 1rem; background: var(--light-color); border-radius: 4px;">
                <strong>Credenciais de teste:</strong>
                <ul style="margin-top: 0.5rem; color: var(--gray-color);">
                    <li><strong>Admin:</strong> admin@sistema.com / admin123</li>
                    <li><strong>Editor:</strong> editor@sistema.com / admin123</li>
                    <li><strong>Visualizador:</strong> joao@email.com / admin123</li>
                </ul>
            </div>
        </section>
    </main>

    <!-- Rodapé semântico -->
    <footer>
        <p>&copy; 2024 Sistema de Gerenciamento de Produtos. Desenvolvido para NP2.</p>
    </footer>

    <script src="../../assets/js/script.js"></script>
</body>
</html>
