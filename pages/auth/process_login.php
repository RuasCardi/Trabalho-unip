<?php
/**
 * Processamento de Login
 * 
 * Valida credenciais e cria sessão segura
 * Protege contra SQL Injection com prepared statements
 * Usa password_verify para validação de senha hash
 */

require_once '../../config/database.php';
require_once '../../config/session.php';

// Verifica se é POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

// Sanitiza e valida entrada
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$senha = $_POST['senha'] ?? '';

// Validação básica
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    setFlashMessage('E-mail inválido.', 'error');
    header('Location: login.php');
    exit;
}

if (strlen($senha) < 6) {
    setFlashMessage('Senha muito curta.', 'error');
    header('Location: login.php');
    exit;
}

try {
    $conn = getConnection();
    
    // Prepared statement para prevenir SQL Injection
    $stmt = $conn->prepare("
        SELECT id, nome, email, senha, tipo_usuario, ativo 
        FROM usuarios 
        WHERE email = ? AND ativo = 1
        LIMIT 1
    ");
    
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    // Verifica se usuário existe e senha está correta
    if ($user && password_verify($senha, $user['senha'])) {
        // Login bem-sucedido
        loginUser($user);
        
        // Registra log de acesso
        $stmt = $conn->prepare("
            INSERT INTO logs_sistema 
            (usuario_id, acao, detalhes, ip_address) 
            VALUES (?, 'LOGIN', 'Login realizado com sucesso', ?)
        ");
        $stmt->execute([$user['id'], $_SERVER['REMOTE_ADDR']]);
        
        setFlashMessage('Login realizado com sucesso! Bem-vindo, ' . $user['nome'] . '!', 'success');
        
        // Redireciona para página solicitada ou dashboard
        $redirect = $_SESSION['redirect_after_login'] ?? '../dashboard.php';
        unset($_SESSION['redirect_after_login']);
        
        header('Location: ' . $redirect);
        exit;
        
    } else {
        // Login falhou - não especificar se é email ou senha
        setFlashMessage('E-mail ou senha incorretos.', 'error');
        
        // Registra tentativa falha
        if ($user) {
            $stmt = $conn->prepare("
                INSERT INTO logs_sistema 
                (usuario_id, acao, detalhes, ip_address) 
                VALUES (?, 'LOGIN_FAILED', 'Tentativa de login com senha incorreta', ?)
            ");
            $stmt->execute([$user['id'], $_SERVER['REMOTE_ADDR']]);
        }
        
        header('Location: login.php');
        exit;
    }
    
} catch (PDOException $e) {
    error_log("Erro no login: " . $e->getMessage());
    setFlashMessage('Erro ao processar login. Tente novamente.', 'error');
    header('Location: login.php');
    exit;
}
