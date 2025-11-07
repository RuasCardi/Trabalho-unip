<?php
/**
 * Gerenciamento de Sessões
 * 
 * Configurações de segurança para sessões PHP
 * Previne Session Hijacking e Session Fixation
 */

// Configurações de sessão seguras
ini_set('session.cookie_httponly', 1);  // Previne acesso via JavaScript
ini_set('session.cookie_secure', 0);    // Use 1 em HTTPS
ini_set('session.use_only_cookies', 1); // Usa apenas cookies
ini_set('session.use_strict_mode', 1);  // Previne session fixation

// Inicia a sessão se ainda não foi iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Verifica se o usuário está autenticado
 * 
 * @return bool True se autenticado, False caso contrário
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && isset($_SESSION['user_email']);
}

/**
 * Verifica se o usuário tem permissão administrativa
 * 
 * @return bool True se é admin, False caso contrário
 */
function isAdmin() {
    return isLoggedIn() && isset($_SESSION['user_tipo']) && $_SESSION['user_tipo'] === 'admin';
}

/**
 * Verifica se o usuário pode editar (admin ou editor)
 * 
 * @return bool True se pode editar, False caso contrário
 */
function canEdit() {
    return isLoggedIn() && isset($_SESSION['user_tipo']) && 
           in_array($_SESSION['user_tipo'], ['admin', 'editor']);
}

/**
 * Redireciona para o login se não estiver autenticado
 */
function requireLogin() {
    if (!isLoggedIn()) {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        header('Location: ' . BASE_URL . '/pages/auth/login.php');
        exit;
    }
}

/**
 * Redireciona para página de acesso negado se não for admin
 */
function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        header('Location: ' . BASE_URL . '/pages/errors/403.php');
        exit;
    }
}

/**
 * Redireciona se não tiver permissão de edição
 */
function requireEdit() {
    requireLogin();
    if (!canEdit()) {
        header('Location: ' . BASE_URL . '/pages/errors/403.php');
        exit;
    }
}

/**
 * Faz login do usuário
 * 
 * @param array $user Dados do usuário
 */
function loginUser($user) {
    // Regenera o ID da sessão para prevenir session fixation
    session_regenerate_id(true);
    
    // Armazena dados do usuário na sessão
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_nome'] = $user['nome'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_tipo'] = $user['tipo_usuario'];
    $_SESSION['login_time'] = time();
    
    // Atualiza último acesso no banco
    try {
        $conn = getConnection();
        $stmt = $conn->prepare("UPDATE usuarios SET ultimo_acesso = NOW() WHERE id = ?");
        $stmt->execute([$user['id']]);
    } catch (PDOException $e) {
        error_log("Erro ao atualizar último acesso: " . $e->getMessage());
    }
}

/**
 * Faz logout do usuário
 */
function logoutUser() {
    // Remove todas as variáveis de sessão
    $_SESSION = [];
    
    // Destroi o cookie de sessão
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    // Destroi a sessão
    session_destroy();
}

/**
 * Obtém o nome do usuário logado
 * 
 * @return string Nome do usuário ou vazio
 */
function getUserName() {
    return $_SESSION['user_nome'] ?? '';
}

/**
 * Obtém o ID do usuário logado
 * 
 * @return int|null ID do usuário ou null
 */
function getUserId() {
    return $_SESSION['user_id'] ?? null;
}

/**
 * Obtém o tipo do usuário logado
 * 
 * @return string|null Tipo do usuário ou null
 */
function getUserType() {
    return $_SESSION['user_tipo'] ?? null;
}

/**
 * Define mensagem de feedback
 * 
 * @param string $message Mensagem
 * @param string $type Tipo (success, error, warning, info)
 */
function setFlashMessage($message, $type = 'info') {
    $_SESSION['flash_message'] = [
        'message' => $message,
        'type' => $type
    ];
}

/**
 * Obtém e remove mensagem de feedback
 * 
 * @return array|null Array com message e type ou null
 */
function getFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $flash = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return $flash;
    }
    return null;
}
