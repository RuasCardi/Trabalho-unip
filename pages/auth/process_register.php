<?php
/**
 * Processamento de Registro
 * 
 * Cria novo usuário com senha hash
 * Valida dados e previne duplicação de e-mail
 */

require_once '../../config/database.php';
require_once '../../config/session.php';

// Verifica se é POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: register.php');
    exit;
}

// Sanitiza entrada
$nome = trim(filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS));
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$senha = $_POST['senha'] ?? '';
$confirmar_senha = $_POST['confirmar_senha'] ?? '';
$tipo_usuario = $_POST['tipo_usuario'] ?? 'visualizador';

// Validações
$erros = [];

if (strlen($nome) < 3) {
    $erros[] = 'O nome deve ter pelo menos 3 caracteres.';
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $erros[] = 'E-mail inválido.';
}

if (strlen($senha) < 6) {
    $erros[] = 'A senha deve ter pelo menos 6 caracteres.';
}

if ($senha !== $confirmar_senha) {
    $erros[] = 'As senhas não coincidem.';
}

// Validar tipo de usuário
$tipos_permitidos = ['visualizador', 'editor'];
if (!in_array($tipo_usuario, $tipos_permitidos)) {
    $tipo_usuario = 'visualizador';
}

if (!empty($erros)) {
    setFlashMessage(implode('<br>', $erros), 'error');
    header('Location: register.php');
    exit;
}

try {
    $conn = getConnection();
    
    // Verifica se e-mail já existe (prepared statement)
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->fetch()) {
        setFlashMessage('Este e-mail já está cadastrado.', 'error');
        header('Location: register.php');
        exit;
    }
    
    // Hash da senha (bcrypt)
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    
    // Insere novo usuário (prepared statement)
    $stmt = $conn->prepare("
        INSERT INTO usuarios (nome, email, senha, tipo_usuario, ativo) 
        VALUES (?, ?, ?, ?, 1)
    ");
    
    $stmt->execute([$nome, $email, $senha_hash, $tipo_usuario]);
    
    // Registra log
    $usuario_id = $conn->lastInsertId();
    $stmt = $conn->prepare("
        INSERT INTO logs_sistema 
        (usuario_id, acao, detalhes, ip_address) 
        VALUES (?, 'REGISTRO', 'Novo usuário registrado', ?)
    ");
    $stmt->execute([$usuario_id, $_SERVER['REMOTE_ADDR']]);
    
    setFlashMessage('Conta criada com sucesso! Faça login para continuar.', 'success');
    header('Location: login.php');
    exit;
    
} catch (PDOException $e) {
    error_log("Erro no registro: " . $e->getMessage());
    setFlashMessage('Erro ao criar conta. Tente novamente.', 'error');
    header('Location: register.php');
    exit;
}
