-- ========================================
-- SISTEMA DE GERENCIAMENTO DE PRODUTOS
-- Script de criação do banco de dados
-- Compatível com integração POO2 (C#)
-- ========================================

-- Criação do banco de dados
CREATE DATABASE IF NOT EXISTS sistema_produtos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sistema_produtos;

-- ========================================
-- TABELA DE USUÁRIOS
-- Armazena dados de autenticação
-- ========================================
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL, -- senha com hash
    tipo_usuario ENUM('admin', 'editor', 'visualizador') DEFAULT 'editor',
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ultimo_acesso TIMESTAMP NULL,
    ativo BOOLEAN DEFAULT TRUE,
    INDEX idx_email (email),
    INDEX idx_tipo (tipo_usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- TABELA DE CATEGORIAS
-- Categorização dos produtos
-- ========================================
CREATE TABLE IF NOT EXISTS categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    ativa BOOLEAN DEFAULT TRUE,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_nome (nome)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- TABELA DE PRODUTOS
-- Tabela principal com relacionamento
-- ========================================
CREATE TABLE IF NOT EXISTS produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(200) NOT NULL,
    descricao TEXT,
    preco DECIMAL(10, 2) NOT NULL,
    quantidade_estoque INT DEFAULT 0,
    categoria_id INT NOT NULL,
    imagem VARCHAR(255) DEFAULT NULL,
    ativo BOOLEAN DEFAULT TRUE,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    usuario_criacao_id INT,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (usuario_criacao_id) REFERENCES usuarios(id) ON DELETE SET NULL ON UPDATE CASCADE,
    INDEX idx_nome (nome),
    INDEX idx_categoria (categoria_id),
    INDEX idx_preco (preco),
    INDEX idx_ativo (ativo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- TABELA DE LOGS (Opcional - para auditoria)
-- Registra ações importantes no sistema
-- ========================================
CREATE TABLE IF NOT EXISTS logs_sistema (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    acao VARCHAR(100) NOT NULL,
    tabela_afetada VARCHAR(50),
    registro_id INT,
    detalhes TEXT,
    ip_address VARCHAR(45),
    data_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL ON UPDATE CASCADE,
    INDEX idx_usuario (usuario_id),
    INDEX idx_data (data_hora)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- INSERÇÃO DE DADOS INICIAIS
-- ========================================

-- Usuário administrador padrão
-- Senha: admin123 (hash gerado com password_hash no PHP)
INSERT INTO usuarios (nome, email, senha, tipo_usuario) VALUES
('Administrador', 'admin@sistema.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Editor Teste', 'editor@sistema.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'editor'),
('João Silva', 'joao@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'visualizador');
git branch -M main
git push -u origin main
-- Categorias de exemplo
INSERT INTO categorias (nome, descricao, ativa) VALUES
('Eletrônicos', 'Produtos eletrônicos e tecnologia', TRUE),
('Alimentos', 'Produtos alimentícios diversos', TRUE),
('Vestuário', 'Roupas e acessórios', TRUE),
('Livros', 'Livros físicos e digitais', TRUE),
('Móveis', 'Móveis e decoração', TRUE),
('Esportes', 'Artigos esportivos e fitness', TRUE);

-- Produtos de exemplo
INSERT INTO produtos (nome, descricao, preco, quantidade_estoque, categoria_id, usuario_criacao_id, ativo) VALUES
('Notebook Dell Inspiron', 'Notebook com processador Intel i5, 8GB RAM, 256GB SSD', 3500.00, 15, 1, 1, TRUE),
('Mouse Logitech MX Master', 'Mouse ergonômico sem fio', 350.00, 50, 1, 1, TRUE),
('Teclado Mecânico RGB', 'Teclado mecânico com iluminação RGB', 450.00, 30, 1, 1, TRUE),
('Arroz Integral 1kg', 'Arroz integral tipo 1', 8.50, 200, 2, 2, TRUE),
('Feijão Preto 1kg', 'Feijão preto selecionado', 7.90, 180, 2, 2, TRUE),
('Camiseta Básica', 'Camiseta 100% algodão', 45.00, 100, 3, 2, TRUE),
('Calça Jeans', 'Calça jeans tradicional', 120.00, 60, 3, 2, TRUE),
('Clean Code', 'Livro sobre código limpo - Robert C. Martin', 65.00, 25, 4, 1, TRUE),
('JavaScript: The Good Parts', 'Livro sobre JavaScript', 58.00, 20, 4, 1, TRUE),
('Cadeira Gamer', 'Cadeira ergonômica para gamers', 890.00, 12, 5, 1, TRUE),
('Mesa de Escritório', 'Mesa com regulagem de altura', 650.00, 8, 5, 1, TRUE),
('Bola de Futebol', 'Bola oficial tamanho 5', 85.00, 40, 6, 2, TRUE),
('Tênis de Corrida', 'Tênis esportivo para corrida', 280.00, 35, 6, 2, TRUE);

-- Log inicial do sistema
INSERT INTO logs_sistema (usuario_id, acao, tabela_afetada, detalhes, ip_address) VALUES
(1, 'INICIALIZACAO', 'sistema', 'Banco de dados criado e populado com dados iniciais', '127.0.0.1');
