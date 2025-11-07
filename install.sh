#!/bin/bash
# Script de instalação automática do Sistema de Produtos
# Execute com: bash install.sh

echo "========================================="
echo "INSTALAÇÃO DO SISTEMA DE PRODUTOS"
echo "========================================="
echo ""

# Cores
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Verifica se o MySQL está rodando
echo "📝 Verificando MySQL..."
if ! command -v mysql &> /dev/null; then
    echo -e "${RED}❌ MySQL não encontrado!${NC}"
    echo "Instale o MySQL e tente novamente."
    exit 1
fi
echo -e "${GREEN}✅ MySQL encontrado${NC}"

# Verifica se o PHP está instalado
echo "📝 Verificando PHP..."
if ! command -v php &> /dev/null; then
    echo -e "${RED}❌ PHP não encontrado!${NC}"
    echo "Instale o PHP 7.4+ e tente novamente."
    exit 1
fi

PHP_VERSION=$(php -r 'echo PHP_VERSION;')
echo -e "${GREEN}✅ PHP $PHP_VERSION encontrado${NC}"

# Cria pasta de uploads
echo "📁 Criando pasta de uploads..."
mkdir -p uploads/produtos
chmod 755 uploads/produtos
echo -e "${GREEN}✅ Pasta criada${NC}"

# Solicita credenciais MySQL
echo ""
echo "🔑 Configuração do Banco de Dados"
read -p "Digite o usuário MySQL (padrão: root): " MYSQL_USER
MYSQL_USER=${MYSQL_USER:-root}

read -sp "Digite a senha do MySQL: " MYSQL_PASS
echo ""

# Cria banco de dados
echo ""
echo "🗄️  Criando banco de dados..."

mysql -u "$MYSQL_USER" -p"$MYSQL_PASS" -e "CREATE DATABASE IF NOT EXISTS sistema_produtos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" 2>/dev/null

if [ $? -eq 0 ]; then
    echo -e "${GREEN}✅ Banco criado${NC}"
else
    echo -e "${RED}❌ Erro ao criar banco${NC}"
    exit 1
fi

# Importa estrutura
echo "📥 Importando estrutura e dados..."
mysql -u "$MYSQL_USER" -p"$MYSQL_PASS" sistema_produtos < sql/database.sql 2>/dev/null

if [ $? -eq 0 ]; then
    echo -e "${GREEN}✅ Dados importados${NC}"
else
    echo -e "${RED}❌ Erro ao importar${NC}"
    exit 1
fi

# Atualiza config/database.php se necessário
if [ "$MYSQL_PASS" != "" ]; then
    echo ""
    echo -e "${YELLOW}⚠️  Atualize a senha em config/database.php${NC}"
fi

echo ""
echo "========================================="
echo -e "${GREEN}✅ INSTALAÇÃO CONCLUÍDA!${NC}"
echo "========================================="
echo ""
echo "🚀 Para iniciar o servidor:"
echo "   php -S localhost:8000"
echo ""
echo "🌐 Acesse no navegador:"
echo "   http://localhost:8000"
echo ""
echo "🔑 Credenciais de teste:"
echo "   Admin: admin@sistema.com / admin123"
echo ""
echo "📚 Leia: LEIA-ME-PRIMEIRO.md"
echo "========================================="
