# 🪟 Instalação e Execução no Windows

## Sistema de Gerenciamento de Produtos - UNIP

Este guia mostrará como instalar e executar o sistema no **Windows** de forma simples e sem erros.

---

## 📋 Pré-requisitos

Você precisará de:
- Windows 7, 8, 10 ou 11
- Conexão com a internet (para download)
- 500 MB de espaço livre em disco

---

## 🚀 Passo a Passo Completo

### 1️⃣ Instalar o XAMPP

O XAMPP é um pacote que inclui **PHP**, **MySQL** e **Apache** (servidor web) tudo em um.

1. **Baixe o XAMPP:**
   - Acesse: https://www.apachefriends.org/download.html
   - Baixe a versão mais recente para Windows (aproximadamente 150 MB)

2. **Instale o XAMPP:**
   - Execute o instalador baixado (`xampp-windows-x64-8.x.x-installer.exe`)
   - Clique em **Next** em todas as telas
   - **Importante:** Instale na pasta padrão `C:\xampp`
   - Aguarde a instalação concluir (3-5 minutos)
   - Clique em **Finish**

3. **Inicie os serviços:**
   - Abra o **XAMPP Control Panel** (deve abrir automaticamente ou procure no Menu Iniciar)
   - Clique no botão **Start** ao lado de **Apache**
   - Clique no botão **Start** ao lado de **MySQL**
   - Os botões devem ficar **verdes** quando iniciados com sucesso

   ![Imagem do XAMPP Control Panel com Apache e MySQL rodando]

---

### 2️⃣ Baixar o Projeto do GitHub

**Opção A: Usando Git (recomendado)**

1. Instale o Git para Windows: https://git-scm.com/download/win
2. Abra o **PowerShell** ou **Prompt de Comando** (CMD)
3. Execute os comandos:

```bash
cd C:\xampp\htdocs
git clone https://github.com/RuasCardi/Trabalho-unip.git
```

**Opção B: Download Direto (sem Git)**

1. Acesse: https://github.com/RuasCardi/Trabalho-unip
2. Clique no botão **Code** (verde) → **Download ZIP**
3. Extraia o arquivo ZIP
4. Mova a pasta extraída para: `C:\xampp\htdocs\`
5. Renomeie a pasta para: `Trabalho-unip` (remova o `-main` se houver)

**Resultado esperado:**
```
C:\xampp\htdocs\Trabalho-unip\
├── index.php
├── config\
├── pages\
├── sql\
└── ...
```

---

### 3️⃣ Criar o Banco de Dados

1. **Acesse o phpMyAdmin:**
   - Abra seu navegador (Chrome, Firefox, Edge)
   - Digite na barra de endereços: `http://localhost/phpmyadmin`
   - Pressione **Enter**

2. **Crie o banco de dados:**
   - No lado esquerdo, clique em **"Novo"** ou **"New"**
   - No campo **"Nome do banco de dados"**, digite: `sistema_produtos`
   - No menu **"Collation"**, selecione: `utf8mb4_unicode_ci`
   - Clique no botão **"Criar"**

3. **Importe as tabelas:**
   - Com o banco `sistema_produtos` selecionado, clique na aba **"Importar"** (no topo)
   - Clique em **"Escolher arquivo"**
   - Navegue até: `C:\xampp\htdocs\Trabalho-unip\sql\database.sql`
   - Selecione o arquivo e clique em **"Abrir"**
   - Role para baixo e clique em **"Executar"** ou **"Go"**
   - Aguarde a mensagem: **"Importação finalizada com sucesso"**

---

### 4️⃣ Criar Usuário do Banco de Dados

No phpMyAdmin:

1. Clique na aba **"SQL"** (no topo da página)
2. Cole o seguinte código SQL na caixa de texto:

```sql
CREATE USER 'webapp'@'localhost' IDENTIFIED BY 'webapp123';
GRANT ALL PRIVILEGES ON sistema_produtos.* TO 'webapp'@'localhost';
FLUSH PRIVILEGES;
```

3. Clique no botão **"Executar"** ou **"Go"**
4. Deve aparecer a mensagem: **"Sua consulta SQL foi executada com sucesso"**

---

### 5️⃣ Configurar Credenciais do Banco (se necessário)

Por padrão, o XAMPP usa o usuário `root` sem senha. Se você criou o usuário `webapp` (passo anterior), pode pular esta etapa.

**Se der erro de conexão**, edite o arquivo de configuração:

1. Abra o arquivo: `C:\xampp\htdocs\Trabalho-unip\config\database.php`
2. Localize as linhas 14-17:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'sistema_produtos');
define('DB_USER', 'webapp');
define('DB_PASS', 'webapp123');
```

3. **Se preferir usar o root**, mude para:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'sistema_produtos');
define('DB_USER', 'root');
define('DB_PASS', '');
```

4. Salve o arquivo (**Ctrl + S**)

---

### 6️⃣ Acessar o Sistema

1. **Abra seu navegador**
2. **Digite na barra de endereços:**

```
http://localhost/Trabalho-unip
```

3. **Pressione Enter**

🎉 **Pronto! O sistema deve abrir!**

---

## 🔐 Credenciais de Acesso

O sistema já vem com 3 usuários cadastrados:

### Administrador (acesso total):
- **Email:** `admin@sistema.com`
- **Senha:** `admin123`

### Editor (pode criar e editar):
- **Email:** `editor@sistema.com`
- **Senha:** `editor123`

### Visualizador (apenas visualizar):
- **Email:** `user@sistema.com`
- **Senha:** `user123`

---

## ✅ Testando o Sistema

Após fazer login, teste as funcionalidades:

1. **Dashboard:** Visualize estatísticas do sistema
2. **Produtos:**
   - Clique em "Produtos" no menu
   - Clique em "Criar Produto"
   - Preencha o formulário e faça upload de uma imagem
   - Clique em "Salvar"
3. **Categorias:**
   - Crie uma nova categoria
   - Edite categorias existentes
4. **Busca:** Use o campo de busca para filtrar produtos
5. **Logout:** Clique em "Sair" para deslogar

---

## 🛠️ Solução de Problemas

### ❌ Erro: "Não foi possível conectar ao banco de dados"

**Solução:**
1. Verifique se o MySQL está rodando no XAMPP Control Panel
2. Verifique as credenciais em `config/database.php`
3. Certifique-se que o banco `sistema_produtos` foi criado

---

### ❌ Apache não inicia (botão fica vermelho)

**Causa:** Outra aplicação está usando a porta 80 (geralmente Skype ou IIS)

**Solução:**
1. Feche o Skype ou outros programas que usam a porta 80
2. Ou altere a porta do Apache:
   - No XAMPP Control Panel, clique em **Config** ao lado de Apache
   - Clique em **httpd.conf**
   - Procure por `Listen 80` e mude para `Listen 8080`
   - Salve e reinicie o Apache
   - Acesse: `http://localhost:8080/Trabalho-unip`

---

### ❌ MySQL não inicia (botão fica vermelho)

**Causa:** Outra instalação do MySQL está rodando

**Solução:**
1. Abra o **Gerenciador de Tarefas** (Ctrl + Shift + Esc)
2. Procure por processos `mysqld.exe` e finalize-os
3. Tente iniciar o MySQL novamente no XAMPP

---

### ❌ Página mostra código PHP ao invés de executar

**Causa:** Apache não está processando arquivos PHP

**Solução:**
1. Verifique se o Apache está rodando
2. Acesse `http://localhost` (sem o caminho do projeto)
3. Deve aparecer a página inicial do XAMPP
4. Se aparecer, o problema é o caminho - use: `http://localhost/Trabalho-unip`

---

### ❌ Imagens de produtos não aparecem

**Solução:**
1. Verifique se a pasta `uploads/produtos/` existe
2. Dê permissão de escrita na pasta (Botão direito → Propriedades → Desmarcar "Somente leitura")
3. Faça upload de uma nova imagem para testar

---

### ❌ "Access Denied for user 'webapp'@'localhost'"

**Solução:**
Edite `config/database.php` e use o usuário `root`:

```php
define('DB_USER', 'root');
define('DB_PASS', '');
```

---

## 📱 Acessando de Outros Dispositivos na Rede

Quer acessar o sistema pelo celular ou outro computador na mesma rede?

1. **Descubra seu IP:**
   - Abra o Prompt de Comando (CMD)
   - Digite: `ipconfig`
   - Procure por **"IPv4 Address"** (exemplo: `192.168.1.100`)

2. **Configure o Firewall:**
   - Permita conexões na porta 80 do Apache

3. **Acesse de outro dispositivo:**
   - Digite no navegador: `http://192.168.1.100/Trabalho-unip`
   - Substitua `192.168.1.100` pelo seu IP real

---

## 🔄 Atualizando o Projeto

Se houver atualizações no GitHub:

1. Abra o PowerShell em `C:\xampp\htdocs\Trabalho-unip`
2. Execute:

```bash
git pull origin main
```

---

## 📊 Estrutura do Banco de Dados

O sistema cria automaticamente 4 tabelas:

- **usuarios** - Armazena usuários do sistema
- **produtos** - Armazena produtos cadastrados
- **categorias** - Armazena categorias de produtos
- **logs_sistema** - Registra ações no sistema

Dados iniciais:
- ✅ 3 usuários (admin, editor, user)
- ✅ 13 produtos de exemplo
- ✅ 6 categorias

---

## 📚 Recursos do Sistema

✅ **Autenticação segura** com bcrypt  
✅ **CRUD completo** de produtos e categorias  
✅ **Upload de imagens** com validação  
✅ **Sistema de busca** e filtros  
✅ **Permissões por nível** (Admin/Editor/Visualizador)  
✅ **Dashboard** com estatísticas  
✅ **Design responsivo** (funciona no celular)  
✅ **Segurança:** SQL Injection e XSS protegidos  
✅ **Integração POO2** (banco compartilhado com C#)  

---

## 💡 Dicas

1. **Backup:** Faça backup do banco antes de testar
   - phpMyAdmin → `sistema_produtos` → Exportar

2. **Desenvolvimento:** Use o navegador em modo anônimo para evitar cache

3. **Logs de erro:** Se algo der errado, verifique:
   - `C:\xampp\apache\logs\error.log`

4. **Documentação completa:** Leia o arquivo `README.md` para mais detalhes

---

## 📞 Suporte

Se tiver problemas:

1. Verifique a seção **Solução de Problemas** acima
2. Leia o arquivo `COMANDOS-UTEIS.md`
3. Consulte a documentação em `docs/README.md`

---

## 🎓 Sobre o Projeto

**Desenvolvido para:** NP2 - Programação Web e POO2  
**Instituição:** UNIP  
**Tecnologias:** PHP 8.3, MySQL 8.0, HTML5, CSS3, JavaScript  
**Repositório:** https://github.com/RuasCardi/Trabalho-unip  

---

## ✅ Checklist de Instalação

Marque conforme conclui cada etapa:

- [ ] XAMPP instalado
- [ ] Apache iniciado (botão verde)
- [ ] MySQL iniciado (botão verde)
- [ ] Projeto baixado em `C:\xampp\htdocs\Trabalho-unip`
- [ ] Banco `sistema_produtos` criado
- [ ] Arquivo `database.sql` importado
- [ ] Usuário `webapp` criado (ou usando `root`)
- [ ] Sistema acessível em `http://localhost/Trabalho-unip`
- [ ] Login realizado com sucesso
- [ ] Produtos e categorias carregando

---

**Data:** 7 de novembro de 2025  
**Versão:** 1.0  
**Autor:** Sistema de Gerenciamento de Produtos - UNIP  

🎉 **Boa sorte com seu projeto!**
