<?php
/**
 * Processamento de Deleção de Categoria
 * 
 * Não permite deletar categoria com produtos
 */

require_once '../../config/database.php';
require_once '../../config/session.php';

// Protege - requer permissão de edição
requireEdit();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    setFlashMessage('Categoria não encontrada.', 'error');
    header('Location: listar.php');
    exit;
}

try {
    $conn = getConnection();
    
    // Verifica se categoria tem produtos
    $stmt = $conn->prepare("
        SELECT COUNT(*) as total 
        FROM produtos 
        WHERE categoria_id = ? AND ativo = 1
    ");
    $stmt->execute([$id]);
    $result = $stmt->fetch();
    
    if ($result['total'] > 0) {
        setFlashMessage('Não é possível deletar categoria com produtos associados.', 'error');
        header('Location: listar.php');
        exit;
    }
    
    // Busca nome da categoria
    $stmt = $conn->prepare("SELECT nome FROM categorias WHERE id = ?");
    $stmt->execute([$id]);
    $categoria = $stmt->fetch();
    
    if (!$categoria) {
        setFlashMessage('Categoria não encontrada.', 'error');
        header('Location: listar.php');
        exit;
    }
    
    // Soft delete
    $stmt = $conn->prepare("UPDATE categorias SET ativa = 0 WHERE id = ?");
    $stmt->execute([$id]);
    
    // Registra log
    $stmt = $conn->prepare("
        INSERT INTO logs_sistema 
        (usuario_id, acao, tabela_afetada, registro_id, detalhes, ip_address) 
        VALUES (?, 'DELETE', 'categorias', ?, ?, ?)
    ");
    $stmt->execute([
        getUserId(),
        $id,
        "Categoria '{$categoria['nome']}' deletada",
        $_SERVER['REMOTE_ADDR']
    ]);
    
    setFlashMessage('Categoria deletada com sucesso!', 'success');
    header('Location: listar.php');
    exit;
    
} catch (PDOException $e) {
    error_log("Erro ao deletar categoria: " . $e->getMessage());
    setFlashMessage('Erro ao deletar categoria.', 'error');
    header('Location: listar.php');
    exit;
}
