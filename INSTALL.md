# 🚀 GUIA RÁPIDO DE INSTALAÇÃO

## ⚡ Instalação em 5 Minutos

### 1️⃣ Configurar Banco de Dados

```bash
# Abra o MySQL
mysql -u root -p

# Execute dentro do MySQL:
```

```sql
CREATE DATABASE sistema_produtos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sistema_produtos;
SOURCE /caminho/completo/para/unip/sql/database.sql;
EXIT;
```

### 2️⃣ Verificar Configurações

Edite `config/database.php` se necessário:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'sistema_produtos');
define('DB_USER', 'root');
define('DB_PASS', ''); // Sua senha do MySQL
```

### 3️⃣ Criar Pasta de Uploads

```bash
cd /caminho/para/unip
mkdir -p uploads/produtos
chmod 755 uploads/produtos
```

### 4️⃣ Iniciar Servidor

```bash
# Opção A: PHP Built-in (Recomendado para testes)
php -S localhost:8000

# Opção B: XAMPP/WAMP
# Copie a pasta 'unip' para htdocs/ e acesse:
# http://localhost/unip
```

### 5️⃣ Acessar Sistema

Abra no navegador:
- **URL**: `http://localhost:8000`
- **Login Admin**: admin@sistema.com / admin123

---

## ✅ CHECKLIST PÓS-INSTALAÇÃO

- [ ] Banco de dados criado e populado
- [ ] Conexão funcionando (sem erros na página inicial)
- [ ] Login funcionando
- [ ] Pasta uploads criada
- [ ] Upload de imagens funcionando

---

## 🐛 PROBLEMAS COMUNS

### Erro: "Connection refused"
**Solução**: Verifique se o MySQL está rodando
```bash
sudo service mysql status
sudo service mysql start
```

### Erro: "Permission denied" na pasta uploads
**Solução**:
```bash
chmod 755 uploads/
chmod 755 uploads/produtos/
```

### Erro: "Call to undefined function password_hash"
**Solução**: Atualize para PHP 7.4+
```bash
php -v  # Verificar versão
```

### Erro ao fazer upload
**Solução**: Aumentar limites no php.ini
```ini
upload_max_filesize = 10M
post_max_size = 10M
```

---

## 📞 SUPORTE

Consulte o **README.md completo** na pasta `docs/` para:
- Documentação detalhada
- Guia de funcionalidades
- Integração com POO2
- Arquitetura do sistema
- Segurança implementada

---

## 🎯 PRÓXIMOS PASSOS

1. ✅ Fazer login com credenciais de teste
2. ✅ Explorar o Dashboard
3. ✅ Criar uma categoria
4. ✅ Criar um produto com imagem
5. ✅ Testar busca e filtros
6. ✅ Testar permissões (editor vs visualizador)

**Pronto! Sistema funcionando! 🎉**
