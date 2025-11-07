<?php
/**
 * Processamento de Criação de Produto
 * 
 * Cria novo produto com upload de imagem
 * Usa prepared statements para segurança
 */

require_once '../../config/database.php';
require_once '../../config/session.php';

// Protege - requer permissão de edição
requireEdit();

// Verifica se é POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: criar.php');
    exit;
}

// Sanitiza entrada
$nome = trim(filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS));
$descricao = trim(filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_SPECIAL_CHARS));
$preco = filter_input(INPUT_POST, 'preco', FILTER_VALIDATE_FLOAT);
$quantidade_estoque = filter_input(INPUT_POST, 'quantidade_estoque', FILTER_VALIDATE_INT);
$categoria_id = filter_input(INPUT_POST, 'categoria_id', FILTER_VALIDATE_INT);
$ativo = isset($_POST['ativo']) ? 1 : 0;
$usuario_criacao_id = getUserId();

// Validações
$erros = [];

if (strlen($nome) < 3) {
    $erros[] = 'O nome deve ter pelo menos 3 caracteres.';
}

if ($preco === false || $preco < 0) {
    $erros[] = 'Preço inválido.';
}

if ($quantidade_estoque === false || $quantidade_estoque < 0) {
    $erros[] = 'Quantidade em estoque inválida.';
}

if (!$categoria_id) {
    $erros[] = 'Selecione uma categoria válida.';
}

if (!empty($erros)) {
    setFlashMessage(implode('<br>', $erros), 'error');
    header('Location: criar.php');
    exit;
}

// Processamento de upload de imagem
$nome_imagem = null;

if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
    $arquivo = $_FILES['imagem'];
    $extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
    
    // Validações de segurança
    if (!in_array($extensao, ALLOWED_EXTENSIONS)) {
        setFlashMessage('Formato de imagem não permitido.', 'error');
        header('Location: criar.php');
        exit;
    }
    
    if ($arquivo['size'] > MAX_FILE_SIZE) {
        setFlashMessage('Arquivo muito grande. Tamanho máximo: 5MB.', 'error');
        header('Location: criar.php');
        exit;
    }
    
    // Valida tipo MIME real do arquivo
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $arquivo['tmp_name']);
    finfo_close($finfo);
    
    $mimes_permitidos = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($mime, $mimes_permitidos)) {
        setFlashMessage('Tipo de arquivo não permitido.', 'error');
        header('Location: criar.php');
        exit;
    }
    
    // Gera nome único para o arquivo
    $nome_imagem = uniqid('produto_') . '_' . time() . '.' . $extensao;
    $caminho_destino = UPLOAD_DIR . $nome_imagem;
    
    // Cria diretório se não existir
    if (!file_exists(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0755, true);
    }
    
    // Move arquivo
    if (!move_uploaded_file($arquivo['tmp_name'], $caminho_destino)) {
        setFlashMessage('Erro ao fazer upload da imagem.', 'error');
        header('Location: criar.php');
        exit;
    }
}

try {
    $conn = getConnection();
    
    // Verifica se categoria existe e está ativa
    $stmt = $conn->prepare("SELECT id FROM categorias WHERE id = ? AND ativa = 1");
    $stmt->execute([$categoria_id]);
    
    if (!$stmt->fetch()) {
        setFlashMessage('Categoria não encontrada ou inativa.', 'error');
        
        // Remove imagem se foi feito upload
        if ($nome_imagem && file_exists(UPLOAD_DIR . $nome_imagem)) {
            unlink(UPLOAD_DIR . $nome_imagem);
        }
        
        header('Location: criar.php');
        exit;
    }
    
    // Insere produto (prepared statement)
    $stmt = $conn->prepare("
        INSERT INTO produtos 
        (nome, descricao, preco, quantidade_estoque, categoria_id, imagem, ativo, usuario_criacao_id) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    $stmt->execute([
        $nome,
        $descricao,
        $preco,
        $quantidade_estoque,
        $categoria_id,
        $nome_imagem,
        $ativo,
        $usuario_criacao_id
    ]);
    
    $produto_id = $conn->lastInsertId();
    
    // Registra log
    $stmt = $conn->prepare("
        INSERT INTO logs_sistema 
        (usuario_id, acao, tabela_afetada, registro_id, detalhes, ip_address) 
        VALUES (?, 'CREATE', 'produtos', ?, ?, ?)
    ");
    $stmt->execute([
        $usuario_criacao_id,
        $produto_id,
        "Produto '{$nome}' cadastrado",
        $_SERVER['REMOTE_ADDR']
    ]);
    
    setFlashMessage('Produto cadastrado com sucesso!', 'success');
    header('Location: visualizar.php?id=' . $produto_id);
    exit;
    
} catch (PDOException $e) {
    error_log("Erro ao criar produto: " . $e->getMessage());
    
    // Remove imagem se foi feito upload
    if ($nome_imagem && file_exists(UPLOAD_DIR . $nome_imagem)) {
        unlink(UPLOAD_DIR . $nome_imagem);
    }
    
    setFlashMessage('Erro ao cadastrar produto. Tente novamente.', 'error');
    header('Location: criar.php');
    exit;
}
