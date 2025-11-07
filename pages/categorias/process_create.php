<?php
/**
 * Processamento de Criação de Categoria
 */

require_once '../../config/database.php';
require_once '../../config/session.php';

// Protege - requer permissão de edição
requireEdit();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: criar.php');
    exit;
}

$nome = trim(filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS));
$descricao = trim(filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_SPECIAL_CHARS));
$ativa = isset($_POST['ativa']) ? 1 : 0;

// Validações
if (strlen($nome) < 3) {
    setFlashMessage('O nome deve ter pelo menos 3 caracteres.', 'error');
    header('Location: criar.php');
    exit;
}

try {
    $conn = getConnection();
    
    // Verifica se categoria já existe
    $stmt = $conn->prepare("SELECT id FROM categorias WHERE nome = ? AND ativa = 1");
    $stmt->execute([$nome]);
    
    if ($stmt->fetch()) {
        setFlashMessage('Já existe uma categoria com este nome.', 'error');
        header('Location: criar.php');
        exit;
    }
    
    // Insere categoria
    $stmt = $conn->prepare("
        INSERT INTO categorias (nome, descricao, ativa) 
        VALUES (?, ?, ?)
    ");
    $stmt->execute([$nome, $descricao, $ativa]);
    
    $categoria_id = $conn->lastInsertId();
    
    // Registra log
    $stmt = $conn->prepare("
        INSERT INTO logs_sistema 
        (usuario_id, acao, tabela_afetada, registro_id, detalhes, ip_address) 
        VALUES (?, 'CREATE', 'categorias', ?, ?, ?)
    ");
    $stmt->execute([
        getUserId(),
        $categoria_id,
        "Categoria '{$nome}' cadastrada",
        $_SERVER['REMOTE_ADDR']
    ]);
    
    setFlashMessage('Categoria cadastrada com sucesso!', 'success');
    header('Location: listar.php');
    exit;
    
} catch (PDOException $e) {
    error_log("Erro ao criar categoria: " . $e->getMessage());
    setFlashMessage('Erro ao cadastrar categoria.', 'error');
    header('Location: criar.php');
    exit;
}
