<?php
/**
 * Processamento de Deleção de Produto
 * 
 * Deleta produto e remove imagem associada
 */

require_once '../../config/database.php';
require_once '../../config/session.php';

// Protege - requer permissão de edição
requireEdit();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    setFlashMessage('Produto não encontrado.', 'error');
    header('Location: listar.php');
    exit;
}

try {
    $conn = getConnection();
    
    // Busca produto para pegar nome e imagem
    $stmt = $conn->prepare("SELECT nome, imagem FROM produtos WHERE id = ? AND ativo = 1");
    $stmt->execute([$id]);
    $produto = $stmt->fetch();
    
    if (!$produto) {
        setFlashMessage('Produto não encontrado.', 'error');
        header('Location: listar.php');
        exit;
    }
    
    // Soft delete - marca como inativo
    $stmt = $conn->prepare("UPDATE produtos SET ativo = 0 WHERE id = ?");
    $stmt->execute([$id]);
    
    // Remove imagem se existir
    if ($produto['imagem'] && file_exists(UPLOAD_DIR . $produto['imagem'])) {
        unlink(UPLOAD_DIR . $produto['imagem']);
    }
    
    // Registra log
    $stmt = $conn->prepare("
        INSERT INTO logs_sistema 
        (usuario_id, acao, tabela_afetada, registro_id, detalhes, ip_address) 
        VALUES (?, 'DELETE', 'produtos', ?, ?, ?)
    ");
    $stmt->execute([
        getUserId(),
        $id,
        "Produto '{$produto['nome']}' deletado",
        $_SERVER['REMOTE_ADDR']
    ]);
    
    setFlashMessage('Produto deletado com sucesso!', 'success');
    header('Location: listar.php');
    exit;
    
} catch (PDOException $e) {
    error_log("Erro ao deletar produto: " . $e->getMessage());
    setFlashMessage('Erro ao deletar produto.', 'error');
    header('Location: listar.php');
    exit;
}
