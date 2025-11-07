# 📚 SISTEMA DE GERENCIAMENTO DE PRODUTOS

## Sistema Web com PHP, MySQL e Integração POO2

### 🎯 Descrição do Projeto

Sistema web completo desenvolvido em PHP e MySQL com autenticação, CRUD, upload de imagens e integração com projeto desktop C# POO2. Atende todos os requisitos da NP2 com implementação de segurança, organização de código e funcionalidades extras.

---

## ✅ CHECKLIST DE REQUISITOS ATENDIDOS

### Estrutura e Organização
- ✅ **HTML Semântico**: Uso correto de `<header>`, `<nav>`, `<main>`, `<section>`, `<article>`, `<footer>`
- ✅ **Elementos HTML**: Headings (h1-h6), parágrafos, listas, links, imagens
- ✅ **Tabelas HTML**: Listagem de produtos e categorias em tabelas bem formatadas
- ✅ **Layout CSS**: Design responsivo com Flexbox e Grid
- ✅ **Elementos semânticos**: header, nav, main, section, article, footer implementados
- ✅ **Formulários**: Validação client-side e server-side
- ✅ **Responsividade**: Adaptação para diferentes tamanhos de tela
- ✅ **Documentação**: Comentários extensivos no código e manual completo

### Segurança
- ✅ **SQL Injection**: Prevenção com prepared statements em TODAS as queries
- ✅ **XSS**: Proteção com `htmlspecialchars()` em todas as saídas
- ✅ **Password Hash**: Uso de `password_hash()` e `password_verify()`
- ✅ **Session Hijacking**: Configurações seguras de sessão
- ✅ **Upload Seguro**: Validação de tipo MIME, extensão e tamanho

### Funcionalidades Core
- ✅ **Login/Registro**: Sistema completo com validação
- ✅ **Sessões**: Controle de autenticação e permissões
- ✅ **Logout**: Destruição segura de sessão
- ✅ **CRUD Produtos**: Create, Read, Update, Delete completo
- ✅ **CRUD Categorias**: Gerenciamento completo
- ✅ **Relacionamentos**: Chave estrangeira entre produtos e categorias
- ✅ **Controle de Permissões**: Admin, Editor, Visualizador

### Funcionalidades Extras (Diferenciais)
- ✅ **Upload de Imagens**: Sistema completo com validação e segurança
- ✅ **Busca Avançada**: Filtros por nome, categoria e faixa de preço
- ✅ **Dashboard**: Estatísticas e visão geral do sistema
- ✅ **Sistema de Logs**: Auditoria de ações importantes
- ✅ **Feedback Visual**: Mensagens de sucesso/erro com auto-dismiss
- ✅ **Design Responsivo**: Mobile-first com breakpoints

### Banco de Dados
- ✅ **Normalização**: Tabelas normalizadas (3FN)
- ✅ **Relacionamentos**: Chaves primárias e estrangeiras
- ✅ **Integridade**: Constraints e validações
- ✅ **Índices**: Otimização de consultas
- ✅ **Compatibilidade POO2**: Estrutura compatível com C#

---

## 🚀 INSTALAÇÃO E CONFIGURAÇÃO

### Pré-requisitos
- PHP 7.4 ou superior
- MySQL 5.7 ou superior
- Servidor web (Apache/Nginx) ou PHP built-in server
- Extensões PHP: PDO, PDO_MySQL, GD (para imagens)

### Passo 1: Configurar Banco de Dados

```bash
# 1. Acesse o MySQL
mysql -u root -p

# 2. Execute o script SQL
source /caminho/para/unip/sql/database.sql
```

Ou pelo phpMyAdmin:
1. Acesse phpMyAdmin
2. Clique em "Importar"
3. Selecione o arquivo `sql/database.sql`
4. Execute

### Passo 2: Configurar Conexão

Edite o arquivo `config/database.php`:

```php
define('DB_HOST', 'localhost');    // Host do banco
define('DB_NAME', 'sistema_produtos'); // Nome do banco
define('DB_USER', 'root');         // Usuário
define('DB_PASS', '');             // Senha
```

### Passo 3: Criar Diretório de Uploads

```bash
mkdir -p uploads/produtos
chmod 755 uploads/produtos
```

### Passo 4: Iniciar Servidor

```bash
# Opção 1: PHP Built-in Server
cd /caminho/para/unip
php -S localhost:8000

# Opção 2: Apache/Nginx
# Configure o DocumentRoot para a pasta do projeto
```

### Passo 5: Acessar Sistema

Abra o navegador e acesse:
- `http://localhost:8000` (PHP built-in)
- `http://localhost/unip` (Apache/Nginx)

---

## 👥 CREDENCIAIS DE TESTE

### Administrador
- **E-mail**: admin@sistema.com
- **Senha**: admin123
- **Permissões**: Todas (criar, editar, deletar, gerenciar usuários)

### Editor
- **E-mail**: editor@sistema.com
- **Senha**: admin123
- **Permissões**: Criar e editar produtos/categorias

### Visualizador
- **E-mail**: joao@email.com
- **Senha**: admin123
- **Permissões**: Apenas visualização

---

## 📁 ESTRUTURA DO PROJETO

```
unip/
├── assets/                 # Recursos estáticos
│   ├── css/
│   │   └── style.css      # Estilos responsivos
│   └── js/
│       └── script.js      # Funções JavaScript
├── config/                 # Configurações
│   ├── database.php       # Conexão PDO (Singleton)
│   └── session.php        # Gerenciamento de sessões
├── pages/                  # Páginas do sistema
│   ├── auth/              # Autenticação
│   │   ├── login.php
│   │   ├── register.php
│   │   ├── logout.php
│   │   ├── process_login.php
│   │   └── process_register.php
│   ├── produtos/          # CRUD Produtos
│   │   ├── listar.php
│   │   ├── criar.php
│   │   ├── editar.php
│   │   ├── visualizar.php
│   │   ├── deletar.php
│   │   └── process_*.php
│   ├── categorias/        # CRUD Categorias
│   │   └── [similar aos produtos]
│   └── dashboard.php      # Dashboard principal
├── sql/                    # Scripts SQL
│   └── database.sql       # Criação e dados iniciais
├── uploads/                # Arquivos enviados
│   └── produtos/          # Imagens de produtos
├── docs/                   # Documentação
│   └── README.md          # Este arquivo
└── index.php              # Página inicial
```

---

## 🔧 FUNCIONALIDADES DETALHADAS

### 1. Sistema de Autenticação

#### Registro de Usuários
- Validação de e-mail único
- Senha com hash bcrypt
- Tipos de usuário: Admin, Editor, Visualizador
- Validação client-side e server-side

#### Login
- Autenticação segura com `password_verify()`
- Prevenção de SQL Injection
- Sessões com configuração segura
- Redirecionamento inteligente

#### Controle de Permissões
- **Admin**: Acesso total + gerenciar usuários
- **Editor**: Criar e editar produtos/categorias
- **Visualizador**: Apenas visualização

### 2. CRUD de Produtos

#### Listar Produtos
- Grid responsivo com cards
- Busca por nome e descrição
- Filtro por categoria
- Filtro por faixa de preço
- Paginação visual
- Indicador de estoque baixo

#### Criar Produto
- Formulário validado
- Upload de imagem (JPG, PNG, GIF, WEBP)
- Validação de tipo MIME
- Preview de imagem
- Associação com categoria
- Controle de estoque

#### Editar Produto
- Carregamento de dados existentes
- Atualização de imagem (opcional)
- Preserva imagem anterior
- Validações completas

#### Deletar Produto
- Confirmação JavaScript
- Soft delete (marca como inativo)
- Remove arquivo de imagem
- Registro em log

#### Visualizar Produto
- Exibição detalhada
- Galeria de imagem
- Informações de categoria
- Histórico de criação
- Botões de ação contextuais

### 3. CRUD de Categorias

#### Funcionalidades
- Listar categorias ativas
- Criar nova categoria
- Editar categoria existente
- Desativar categoria
- Contador de produtos por categoria
- Validação de exclusão (protege se tiver produtos)

### 4. Upload de Imagens (FUNCIONALIDADE EXTRA)

#### Segurança Implementada
- ✅ Validação de extensão (whitelist)
- ✅ Validação de tipo MIME real (finfo)
- ✅ Limite de tamanho (5MB)
- ✅ Nome de arquivo único (uniqid + timestamp)
- ✅ Pasta com permissões restritas
- ✅ Preview antes do upload
- ✅ Remoção ao deletar produto

#### Formatos Aceitos
- JPEG (.jpg, .jpeg)
- PNG (.png)
- GIF (.gif)
- WebP (.webp)

### 5. Busca Avançada (FUNCIONALIDADE EXTRA)

#### Filtros Disponíveis
- **Texto**: Nome ou descrição (LIKE com wildcard)
- **Categoria**: Dropdown com categorias ativas
- **Preço Mínimo**: Filtro >= preço
- **Preço Máximo**: Filtro <= preço
- **Combinação**: Todos os filtros podem ser combinados

#### Implementação
- Query dinâmica com prepared statements
- Parâmetros sanitizados
- URL amigável com GET
- Botão "Limpar" para resetar

### 6. Dashboard

#### Estatísticas
- Total de produtos ativos
- Total de categorias ativas
- Valor total em estoque
- Total de usuários (admin only)

#### Alertas
- Produtos com estoque baixo (< 10)
- Últimos produtos cadastrados
- Ações rápidas contextuais

### 7. Sistema de Logs (FUNCIONALIDADE EXTRA)

#### Eventos Registrados
- Login/Logout
- Criação de registros
- Edição de registros
- Exclusão de registros
- Tentativas de login falhadas

#### Informações Armazenadas
- ID do usuário
- Tipo de ação
- Tabela afetada
- ID do registro
- Detalhes da ação
- IP address
- Data/hora

---

## 🔐 SEGURANÇA IMPLEMENTADA

### SQL Injection
```php
// ❌ ERRADO (vulnerável)
$query = "SELECT * FROM usuarios WHERE email = '$email'";

// ✅ CORRETO (seguro)
$stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
$stmt->execute([$email]);
```

### XSS (Cross-Site Scripting)
```php
// ❌ ERRADO (vulnerável)
echo $user['nome'];

// ✅ CORRETO (seguro)
echo htmlspecialchars($user['nome'], ENT_QUOTES, 'UTF-8');
```

### Password Hashing
```php
// Criação
$hash = password_hash($senha, PASSWORD_DEFAULT);

// Verificação
if (password_verify($senha, $hash)) {
    // Login correto
}
```

### Upload de Arquivos
```php
// 1. Validação de extensão
$ext = pathinfo($arquivo['name'], PATHINFO_EXTENSION);
if (!in_array($ext, ['jpg', 'png', 'gif', 'webp'])) {
    die('Extensão não permitida');
}

// 2. Validação de tipo MIME real
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $arquivo['tmp_name']);
if (!in_array($mime, ['image/jpeg', 'image/png', ...])) {
    die('Tipo de arquivo não permitido');
}

// 3. Nome único
$nome = uniqid('produto_') . '_' . time() . '.' . $ext;
```

### Sessões Seguras
```php
// Configurações
ini_set('session.cookie_httponly', 1);  // Previne JavaScript
ini_set('session.cookie_secure', 0);    // Use 1 em HTTPS
ini_set('session.use_only_cookies', 1);
ini_set('session.use_strict_mode', 1);  // Previne fixation

// Regeneração de ID ao fazer login
session_regenerate_id(true);
```

---

## 🔗 INTEGRAÇÃO COM POO2 (C#)

### Compatibilidade do Banco de Dados

O banco de dados foi projetado para ser **totalmente compatível** com o projeto desktop em C# POO2.

#### Tabelas Compartilhadas

1. **usuarios**: Autenticação em ambos os sistemas
2. **categorias**: Mesma estrutura de categorização
3. **produtos**: Dados de produtos sincronizados
4. **logs_sistema**: Auditoria unificada

#### Exemplo de Conexão C#

```csharp
using MySql.Data.MySqlClient;

public class Database
{
    private string connectionString = "Server=localhost;Database=sistema_produtos;Uid=root;Pwd=;";
    
    public MySqlConnection GetConnection()
    {
        return new MySqlConnection(connectionString);
    }
}

// Exemplo de consulta
public List<Produto> GetProdutos()
{
    var produtos = new List<Produto>();
    using (var conn = db.GetConnection())
    {
        conn.Open();
        var cmd = new MySqlCommand(
            "SELECT p.*, c.nome as categoria_nome " +
            "FROM produtos p " +
            "JOIN categorias c ON p.categoria_id = c.id " +
            "WHERE p.ativo = 1", 
            conn
        );
        
        using (var reader = cmd.ExecuteReader())
        {
            while (reader.Read())
            {
                produtos.Add(new Produto
                {
                    Id = reader.GetInt32("id"),
                    Nome = reader.GetString("nome"),
                    Preco = reader.GetDecimal("preco"),
                    // ... demais campos
                });
            }
        }
    }
    return produtos;
}
```

#### Sincronização de Dados

- Ambos os sistemas usam o **mesmo banco MySQL**
- Alterações no sistema web são refletidas no desktop
- Alterações no sistema desktop são refletidas no web
- Logs unificados para auditoria completa

#### Campos Específicos

- `usuario_criacao_id`: Rastreamento de quem criou (web ou desktop)
- `data_criacao`: Timestamp de criação
- `data_atualizacao`: Timestamp de última modificação
- `ativo`: Soft delete compartilhado

---

## 📊 BANCO DE DADOS

### Diagrama ER (Simplificado)

```
┌──────────────┐         ┌────────────────┐         ┌──────────────┐
│   usuarios   │         │   categorias   │         │  produtos    │
├──────────────┤         ├────────────────┤         ├──────────────┤
│ id (PK)      │         │ id (PK)        │         │ id (PK)      │
│ nome         │    ┌───>│ nome           │<────┐   │ nome         │
│ email (UK)   │    │    │ descricao      │     │   │ descricao    │
│ senha        │    │    │ ativa          │     │   │ preco        │
│ tipo_usuario │    │    │ data_criacao   │     └───│ categoria_id │
│ ativo        │    │    └────────────────┘         │ imagem       │
│ data_criacao │    │                               │ estoque      │
│ ultimo_acesso│────┘                               │ ativo        │
└──────────────┘                                    │ usuario_id   │
                                                    └──────────────┘
```

### Queries Otimizadas

Todos os índices necessários foram criados:

```sql
-- Índices em usuarios
INDEX idx_email (email)
INDEX idx_tipo (tipo_usuario)

-- Índices em categorias
INDEX idx_nome (nome)

-- Índices em produtos
INDEX idx_nome (nome)
INDEX idx_categoria (categoria_id)
INDEX idx_preco (preco)
INDEX idx_ativo (ativo)

-- Índices em logs
INDEX idx_usuario (usuario_id)
INDEX idx_data (data_hora)
```

---

## 🎨 DESIGN RESPONSIVO

### Breakpoints

```css
/* Desktop: >= 769px */
/* Tablet/Mobile: <= 768px */

@media (max-width: 768px) {
    /* Navegação em coluna */
    /* Grid de produtos em 1 coluna */
    /* Formulários em layout único */
    /* Tabelas com scroll horizontal */
}
```

### Features Responsivas
- Navegação adaptável
- Grid fluido de produtos
- Formulários ajustáveis
- Tabelas scrolláveis
- Imagens responsivas
- Typography escalável

---

## 🧪 TESTANDO O SISTEMA

### Checklist de Testes

1. **Autenticação**
   - [ ] Registrar novo usuário
   - [ ] Login com credenciais corretas
   - [ ] Tentativa de login com senha errada
   - [ ] Logout

2. **Produtos**
   - [ ] Listar todos os produtos
   - [ ] Criar produto sem imagem
   - [ ] Criar produto com imagem
   - [ ] Editar produto
   - [ ] Deletar produto
   - [ ] Buscar por nome
   - [ ] Filtrar por categoria
   - [ ] Filtrar por preço

3. **Categorias**
   - [ ] Listar categorias
   - [ ] Criar categoria
   - [ ] Editar categoria
   - [ ] Tentar deletar categoria com produtos

4. **Permissões**
   - [ ] Visualizador não pode editar
   - [ ] Editor pode criar/editar
   - [ ] Admin tem acesso total

5. **Segurança**
   - [ ] Tentar acessar página protegida sem login
   - [ ] Tentar upload de arquivo .php
   - [ ] Tentar SQL injection em busca

---

## 📝 RECURSOS EXTRAS IMPLEMENTADOS

### 1. Upload de Imagens ⭐
- Validação completa de segurança
- Preview antes do envio
- Gerenciamento de arquivos
- Fallback para produtos sem imagem

### 2. Busca Avançada ⭐
- Múltiplos filtros combinados
- Query otimizada
- Interface intuitiva
- URL amigável

### 3. Sistema de Logs ⭐
- Auditoria completa
- Rastreamento de ações
- IP tracking
- Histórico detalhado

### 4. Dashboard Interativo ⭐
- Estatísticas em tempo real
- Alertas de estoque baixo
- Ações rápidas contextuais
- Design moderno

### 5. Controle de Permissões ⭐
- Três níveis de acesso
- Proteção por página
- Verificação em actions
- Interface adaptável

---

## 🚦 COMO APRESENTAR O PROJETO

### Roteiro de Apresentação

1. **Introdução (2 min)**
   - Apresentar equipe
   - Visão geral do sistema
   - Tecnologias utilizadas

2. **Demonstração de Funcionalidades (8 min)**
   - Login e autenticação
   - Dashboard e estatísticas
   - CRUD de produtos com upload
   - Busca avançada e filtros
   - CRUD de categorias
   - Sistema de permissões

3. **Aspectos Técnicos (5 min)**
   - Segurança (SQL Injection, XSS, Password Hash)
   - Estrutura do código
   - Padrão Singleton na conexão
   - Prepared statements
   - Upload seguro de imagens

4. **Integração POO2 (3 min)**
   - Banco de dados unificado
   - Compatibilidade C#
   - Exemplo de consulta
   - Sincronização de dados

5. **Recursos Extras (2 min)**
   - Upload de imagens
   - Busca avançada
   - Sistema de logs
   - Dashboard interativo

6. **Perguntas (5 min)**

### Pontos Fortes para Destacar

✅ **Segurança em primeiro lugar**
✅ **Código limpo e documentado**
✅ **Design responsivo e moderno**
✅ **Funcionalidades além do requisitado**
✅ **Integração real com POO2**
✅ **Arquitetura escalável**

---

## 🐛 TROUBLESHOOTING

### Erro: "Connection refused"
- Verifique se o MySQL está rodando
- Confira as credenciais em `config/database.php`
- Teste a conexão: `mysql -u root -p`

### Erro: "Upload failed"
- Verifique permissões da pasta `uploads/`: `chmod 755 uploads/`
- Confira configurações PHP: `upload_max_filesize` e `post_max_size`
- Verifique se a pasta existe

### Erro: "Session not working"
- Verifique permissões da pasta de sessões do PHP
- Confira se cookies estão habilitados no navegador
- Em HTTPS, ajuste `session.cookie_secure` para 1

### Erro: "PDO driver not found"
- Instale extensão: `sudo apt-get install php-mysql`
- Habilite no php.ini: `extension=pdo_mysql`
- Reinicie o servidor web

---

## 📞 SUPORTE E CONTATO

### Documentação Adicional
- Comentários inline no código
- PHPDoc em funções principais
- README detalhado (este arquivo)

### Estrutura de Arquivos
Todos os arquivos estão comentados explicando:
- Propósito do arquivo
- Parâmetros de funções
- Validações implementadas
- Medidas de segurança

---

## 🏆 CONCLUSÃO

Este projeto atende **100% dos requisitos** da NP2, incluindo:

✅ Sistema de login com senha hash  
✅ CRUD completo com PDO  
✅ Prepared statements (SQL Injection)  
✅ Proteção XSS (htmlspecialchars)  
✅ Relacionamento entre tabelas  
✅ Estrutura organizada  
✅ Banco de dados integrado com POO2  
✅ Funcionalidade extra: Upload de imagens  
✅ Funcionalidade extra: Busca avançada  
✅ Funcionalidade extra: Sistema de logs  
✅ Documentação completa  
✅ HTML semântico  
✅ Design responsivo  
✅ Validações client/server  

**Diferencial**: Sistema profissional, escalável e pronto para produção!

---

**Desenvolvido para NP2 - UNIP**  
**Disciplinas**: Programação Web e Programação Orientada a Objetos 2  
**Ano**: 2024
