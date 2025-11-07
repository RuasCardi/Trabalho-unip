# 🚀 INSTALAÇÃO COMPLETA - Ubuntu/Debian

## ⚠️ VOCÊ NÃO TEM MySQL INSTALADO

Siga este guia passo a passo para instalar tudo que precisa.

---

## 📦 PASSO 1: INSTALAR MySQL

### Opção A: MySQL Server (Recomendado)

```bash
# Atualizar repositórios
sudo apt update

# Instalar MySQL Server
sudo apt install mysql-server -y

# Verificar se está rodando
sudo systemctl status mysql

# Se não estiver rodando, iniciar
sudo systemctl start mysql

# Configurar para iniciar automaticamente
sudo systemctl enable mysql
```

### Opção B: MariaDB (Alternativa compatível)

```bash
# Instalar MariaDB (substituto do MySQL)
sudo apt install mariadb-server -y

# Iniciar serviço
sudo systemctl start mariadb
sudo systemctl enable mariadb
```

---

## 🔐 PASSO 2: CONFIGURAR MySQL

### Primeira vez - Sem senha

```bash
# Acessar MySQL como root (primeira vez não pede senha)
sudo mysql

# Dentro do MySQL, execute:
```

```sql
-- Criar senha para o usuário root
ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'sua_senha_aqui';

-- Ou sem senha (não recomendado em produção):
ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY '';

-- Aplicar mudanças
FLUSH PRIVILEGES;

-- Sair
EXIT;
```

### Testar login

```bash
# Com senha
mysql -u root -p
# Digite a senha quando solicitado

# Sem senha (se configurou sem senha)
mysql -u root
```

---

## 🗄️ PASSO 3: CRIAR O BANCO DE DADOS

```bash
# Entrar no MySQL
sudo mysql
# ou
mysql -u root -p
```

```sql
-- Criar banco
CREATE DATABASE sistema_produtos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Usar o banco
USE sistema_produtos;

-- Importar estrutura
SOURCE /home/guilherme-cardinalli/Área\ de\ trabalho/unip/sql/database.sql;

-- Verificar tabelas criadas
SHOW TABLES;

-- Ver alguns dados
SELECT COUNT(*) FROM produtos;
SELECT COUNT(*) FROM usuarios;

-- Sair
EXIT;
```

---

## 📦 PASSO 4: INSTALAR PHP (se necessário)

```bash
# Verificar se PHP está instalado
php -v

# Se não estiver instalado:
sudo apt update
sudo apt install php php-mysql php-mbstring php-cli -y

# Verificar novamente
php -v

# Verificar extensões necessárias
php -m | grep pdo
php -m | grep mysqli
```

---

## ✅ PASSO 5: AJUSTAR CONFIGURAÇÃO DO PROJETO

Se você configurou uma senha para o MySQL, edite o arquivo:

```bash
nano config/database.php
```

Altere a linha:
```php
define('DB_PASS', '');  // Coloque sua senha aqui
```

Para:
```php
define('DB_PASS', 'sua_senha_aqui');
```

Salve: `Ctrl + O`, Enter, `Ctrl + X`

---

## 🚀 PASSO 6: INICIAR O SISTEMA

```bash
# Navegar para pasta do projeto
cd "/home/guilherme-cardinalli/Área de trabalho/unip"

# Verificar permissões da pasta uploads
chmod 755 uploads/produtos/

# Iniciar servidor PHP
php -S localhost:8000
```

---

## 🌐 PASSO 7: ACESSAR NO NAVEGADOR

Abra seu navegador em: **http://localhost:8000**

**Credenciais de teste:**
- Email: `admin@sistema.com`
- Senha: `admin123`

---

## 🔧 COMANDOS ÚTEIS MYSQL

### Verificar se MySQL está rodando
```bash
sudo systemctl status mysql
# ou
sudo systemctl status mariadb
```

### Iniciar MySQL
```bash
sudo systemctl start mysql
```

### Parar MySQL
```bash
sudo systemctl stop mysql
```

### Reiniciar MySQL
```bash
sudo systemctl restart mysql
```

### Ver logs de erro
```bash
sudo tail -f /var/log/mysql/error.log
```

---

## 🐛 SOLUÇÃO DE PROBLEMAS

### Erro: "Access denied for user 'root'@'localhost'"

**Solução 1: Usar sudo**
```bash
sudo mysql
```

**Solução 2: Resetar senha do root**
```bash
sudo mysql
```
```sql
ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'nova_senha';
FLUSH PRIVILEGES;
EXIT;
```

### Erro: "Can't connect to local MySQL server"

**MySQL não está rodando:**
```bash
sudo systemctl start mysql
```

### Erro: "Unknown database 'sistema_produtos'"

**Banco não foi criado:**
```bash
sudo mysql
```
```sql
CREATE DATABASE sistema_produtos;
USE sistema_produtos;
SOURCE /home/guilherme-cardinalli/Área\ de\ trabalho/unip/sql/database.sql;
EXIT;
```

---

## 📋 SCRIPT DE INSTALAÇÃO RÁPIDA

Copie e cole este script completo no terminal:

```bash
# Instalar MySQL
sudo apt update
sudo apt install mysql-server -y

# Iniciar MySQL
sudo systemctl start mysql
sudo systemctl enable mysql

# Criar banco e importar dados
sudo mysql <<EOF
CREATE DATABASE IF NOT EXISTS sistema_produtos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sistema_produtos;
SOURCE /home/guilherme-cardinalli/Área\ de\ trabalho/unip/sql/database.sql;
EXIT;
EOF

# Garantir permissões
cd "/home/guilherme-cardinalli/Área de trabalho/unip"
chmod 755 uploads/produtos/

echo "✅ Instalação concluída!"
echo "🚀 Inicie o servidor com: php -S localhost:8000"
```

---

## 🎯 RESUMO DOS COMANDOS

```bash
# 1. Instalar MySQL
sudo apt install mysql-server -y

# 2. Iniciar MySQL
sudo systemctl start mysql

# 3. Acessar MySQL
sudo mysql

# 4. No MySQL, criar e importar:
CREATE DATABASE sistema_produtos;
USE sistema_produtos;
SOURCE /home/guilherme-cardinalli/Área\ de\ trabalho/unip/sql/database.sql;
EXIT;

# 5. Iniciar servidor PHP
cd "/home/guilherme-cardinalli/Área de trabalho/unip"
php -S localhost:8000

# 6. Acessar: http://localhost:8000
# Login: admin@sistema.com / admin123
```

---

## ✅ CHECKLIST DE INSTALAÇÃO

- [ ] MySQL instalado
- [ ] MySQL rodando (`sudo systemctl status mysql`)
- [ ] Banco `sistema_produtos` criado
- [ ] Tabelas importadas (4 tabelas)
- [ ] PHP instalado e funcionando
- [ ] Servidor iniciado (`php -S localhost:8000`)
- [ ] Sistema acessível em http://localhost:8000
- [ ] Login funcionando

---

## 💡 DICAS

### Para desenvolvimento, você pode usar sem senha:

```bash
sudo mysql
```
```sql
ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY '';
FLUSH PRIVILEGES;
EXIT;
```

Depois, acesse com:
```bash
mysql -u root
```

### Para tornar mais fácil, adicione ao ~/.bashrc:
```bash
echo "alias mysql-start='sudo systemctl start mysql'" >> ~/.bashrc
echo "alias mysql-stop='sudo systemctl stop mysql'" >> ~/.bashrc
source ~/.bashrc
```

Agora você pode usar:
```bash
mysql-start  # Iniciar MySQL
mysql-stop   # Parar MySQL
```

---

## 🎉 PRONTO!

Após seguir estes passos, seu sistema estará funcionando perfeitamente!

**Próximo passo:** Abra http://localhost:8000 e faça login!

---

**Precisa de ajuda? Consulte COMANDOS-UTEIS.md**
