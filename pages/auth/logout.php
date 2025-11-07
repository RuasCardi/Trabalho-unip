<?php
/**
 * Logout
 * 
 * Destroi a sessão e redireciona para o login
 */

require_once '../../config/database.php';
require_once '../../config/session.php';

// Registra log de logout antes de destruir sessão
if (isLoggedIn()) {
    try {
        $conn = getConnection();
        $stmt = $conn->prepare("
            INSERT INTO logs_sistema 
            (usuario_id, acao, detalhes, ip_address) 
            VALUES (?, 'LOGOUT', 'Logout realizado', ?)
        ");
        $stmt->execute([getUserId(), $_SERVER['REMOTE_ADDR']]);
    } catch (PDOException $e) {
        error_log("Erro ao registrar logout: " . $e->getMessage());
    }
}

// Faz logout
logoutUser();

setFlashMessage('Você saiu do sistema com sucesso.', 'info');
header('Location: login.php');
exit;
