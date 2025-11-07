<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acesso Negado</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <header>
        <nav>
            <a href="../../index.php" class="nav-brand">🛍️ Sistema de Produtos</a>
            <ul class="nav-menu">
                <li><a href="../../index.php">Início</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section style="text-align: center; padding: 4rem 2rem;">
            <div style="font-size: 6rem; margin-bottom: 1rem;">🔒</div>
            <h1 style="color: var(--danger-color); font-size: 3rem;">Acesso Negado</h1>
            <p style="font-size: 1.25rem; color: var(--gray-color); margin: 2rem 0;">
                Você não tem permissão para acessar esta página.
            </p>
            
            <div style="background: var(--light-color); padding: 2rem; border-radius: 8px; max-width: 600px; margin: 2rem auto; text-align: left;">
                <h3 style="margin-bottom: 1rem;">💡 Possíveis motivos:</h3>
                <ul style="list-style-position: inside; color: var(--gray-color);">
                    <li>Você não está autenticado no sistema</li>
                    <li>Seu tipo de usuário não tem permissão para esta ação</li>
                    <li>A página requer permissões de administrador</li>
                    <li>A sessão pode ter expirado</li>
                </ul>
            </div>
            
            <div class="btn-group" style="justify-content: center; margin-top: 2rem;">
                <a href="../../index.php" class="btn btn-primary">
                    🏠 Voltar para Início
                </a>
                <a href="../auth/login.php" class="btn btn-secondary">
                    🔑 Fazer Login
                </a>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Sistema de Gerenciamento de Produtos</p>
    </footer>
</body>
</html>
