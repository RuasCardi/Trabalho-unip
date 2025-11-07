# ⚡ COMANDOS ÚTEIS

## 🚀 Instalação Rápida

### Opção 1: Script Automático (Linux/Mac)
```bash
cd "/home/guilherme-cardinalli/Área de trabalho/unip"
bash install.sh
```

### Opção 2: Manual
```bash
# 1. Criar banco
mysql -u root -p
```
```sql
CREATE DATABASE sistema_produtos;
USE sistema_produtos;
SOURCE /home/guilherme-cardinalli/Área\ de\ trabalho/unip/sql/database.sql;
EXIT;
```

```bash
# 2. Criar pasta uploads
mkdir -p uploads/produtos
chmod 755 uploads/produtos

# 3. Iniciar servidor
php -S localhost:8000
```

---

## 🔧 Comandos do Banco de Dados

### Verificar instalação
```sql
-- Conectar
mysql -u root -p sistema_produtos

-- Ver tabelas
SHOW TABLES;

-- Contar registros
SELECT COUNT(*) FROM produtos;
SELECT COUNT(*) FROM categorias;
SELECT COUNT(*) FROM usuarios;

-- Ver usuários de teste
SELECT id, nome, email, tipo_usuario FROM usuarios;
```

### Resetar banco (se necessário)
```sql
DROP DATABASE sistema_produtos;
CREATE DATABASE sistema_produtos;
USE sistema_produtos;
SOURCE /home/guilherme-cardinalli/Área\ de\ trabalho/unip/sql/database.sql;
```

### Backup
```bash
# Fazer backup
mysqldump -u root -p sistema_produtos > backup_$(date +%Y%m%d).sql

# Restaurar backup
mysql -u root -p sistema_produtos < backup_20241107.sql
```

---

## 🌐 Comandos do Servidor

### Iniciar servidor
```bash
# PHP Built-in (recomendado para desenvolvimento)
cd "/home/guilherme-cardinalli/Área de trabalho/unip"
php -S localhost:8000

# Ou em outra porta
php -S localhost:3000
```

### Verificar PHP
```bash
# Ver versão
php -v

# Ver extensões instaladas
php -m | grep -i pdo
php -m | grep -i mysql
```

### Parar servidor
```
Ctrl + C no terminal
```

---

## 📁 Comandos de Arquivos

### Permissões
```bash
# Dar permissão na pasta uploads
chmod 755 uploads/produtos/

# Recursivo (se necessário)
chmod -R 755 uploads/

# Ver permissões
ls -la uploads/
```

### Limpar uploads (cuidado!)
```bash
# Remover todas as imagens de produtos
rm uploads/produtos/*.jpg
rm uploads/produtos/*.png
rm uploads/produtos/*.gif
rm uploads/produtos/*.webp

# Ou todas de uma vez
rm uploads/produtos/produto_*
```

---

## 🧪 Testes Rápidos

### Testar conexão com banco
```bash
php -r "
\$conn = new PDO('mysql:host=localhost;dbname=sistema_produtos', 'root', '');
echo 'Conexão OK!';
"
```

### Testar hash de senha
```bash
php -r "
\$hash = password_hash('admin123', PASSWORD_DEFAULT);
echo 'Hash: ' . \$hash . PHP_EOL;
echo 'Verify: ' . (password_verify('admin123', \$hash) ? 'OK' : 'FAIL');
"
```

### Listar arquivos do projeto
```bash
cd "/home/guilherme-cardinalli/Área de trabalho/unip"
find . -type f -name "*.php" | wc -l  # Contar arquivos PHP
find . -type f | wc -l                # Contar todos os arquivos
```

---

## 🔍 Debug e Logs

### Ver erros PHP
```bash
# No terminal onde o servidor está rodando
# Os erros aparecerão automaticamente

# Ou ative no php.ini
display_errors = On
error_reporting = E_ALL
```

### Ver logs MySQL
```bash
# Logs de erro (varia por instalação)
sudo tail -f /var/log/mysql/error.log

# Ou
sudo tail -f /var/log/mysql/mysql.log
```

### Testar arquivo específico
```bash
php pages/auth/login.php  # Vai mostrar erros se houver
```

---

## 📊 Consultas Úteis SQL

### Ver produtos mais caros
```sql
SELECT nome, preco FROM produtos ORDER BY preco DESC LIMIT 5;
```

### Ver produtos sem estoque
```sql
SELECT nome, quantidade_estoque FROM produtos WHERE quantidade_estoque = 0;
```

### Ver produtos por categoria
```sql
SELECT c.nome as categoria, COUNT(p.id) as total
FROM categorias c
LEFT JOIN produtos p ON c.id = p.categoria_id
GROUP BY c.id;
```

### Ver logs recentes
```sql
SELECT * FROM logs_sistema ORDER BY data_hora DESC LIMIT 10;
```

### Ver usuários e último acesso
```sql
SELECT nome, email, tipo_usuario, ultimo_acesso 
FROM usuarios 
ORDER BY ultimo_acesso DESC;
```

---

## 🛠️ Manutenção

### Limpar logs antigos (se ficar muito grande)
```sql
DELETE FROM logs_sistema WHERE data_hora < DATE_SUB(NOW(), INTERVAL 30 DAY);
```

### Ver tamanho do banco
```sql
SELECT 
    table_name AS 'Tabela',
    ROUND(((data_length + index_length) / 1024 / 1024), 2) AS 'Tamanho (MB)'
FROM information_schema.TABLES
WHERE table_schema = 'sistema_produtos'
ORDER BY (data_length + index_length) DESC;
```

### Otimizar tabelas
```sql
OPTIMIZE TABLE produtos;
OPTIMIZE TABLE categorias;
OPTIMIZE TABLE usuarios;
OPTIMIZE TABLE logs_sistema;
```

---

## 🎯 Comandos para Demonstração

### Preparar para apresentação
```bash
# 1. Limpar dados de teste (opcional)
mysql -u root -p sistema_produtos -e "
DELETE FROM produtos WHERE id > 13;
DELETE FROM categorias WHERE id > 6;
"

# 2. Resetar tudo
mysql -u root -p sistema_produtos < sql/database.sql

# 3. Iniciar servidor
php -S localhost:8000

# 4. Abrir navegador
xdg-open http://localhost:8000  # Linux
# ou
open http://localhost:8000      # Mac
```

---

## 📦 Exportar/Importar

### Exportar apenas estrutura
```bash
mysqldump -u root -p --no-data sistema_produtos > estrutura.sql
```

### Exportar apenas dados
```bash
mysqldump -u root -p --no-create-info sistema_produtos > dados.sql
```

### Exportar tudo
```bash
mysqldump -u root -p sistema_produtos > completo.sql
```

---

## 🔗 URLs Úteis

Após iniciar o servidor em `localhost:8000`:

- **Home**: http://localhost:8000
- **Login**: http://localhost:8000/pages/auth/login.php
- **Registro**: http://localhost:8000/pages/auth/register.php
- **Dashboard**: http://localhost:8000/pages/dashboard.php
- **Produtos**: http://localhost:8000/pages/produtos/listar.php
- **Categorias**: http://localhost:8000/pages/categorias/listar.php

---

## 💡 Dicas

### Recarregar página sem cache
```
Ctrl + Shift + R (Chrome/Firefox)
Cmd + Shift + R (Mac)
```

### Inspecionar elemento
```
F12 ou Ctrl + Shift + I
```

### Ver código fonte
```
Ctrl + U
```

---

## 🚨 Solução de Problemas

### Porta 8000 já está em uso
```bash
# Use outra porta
php -S localhost:3000
php -S localhost:8080
```

### Erro "Cannot modify header"
```bash
# Verifique se não há espaços antes de <?php
# Verifique se não há echo antes de header()
```

### Erro "PDO driver not found"
```bash
# Ubuntu/Debian
sudo apt-get install php-mysql

# Fedora
sudo dnf install php-mysqlnd

# Verificar
php -m | grep pdo
```

---

**Salve este arquivo como referência! 📌**
