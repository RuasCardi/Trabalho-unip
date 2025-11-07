<?php
/**
 * Configuração de Conexão com Banco de Dados
 * 
 * Este arquivo contém as configurações de conexão com MySQL
 * utilizando PDO (PHP Data Objects) para maior segurança
 * e compatibilidade com o projeto POO2.
 * 
 * IMPORTANTE: Em produção, use variáveis de ambiente para
 * armazenar credenciais sensíveis.
 */

// Configurações do banco de dados
define('DB_HOST', 'localhost');
define('DB_NAME', 'sistema_produtos');
define('DB_USER', 'webapp');
define('DB_PASS', 'webapp123');
define('DB_CHARSET', 'utf8mb4');

// Configurações da aplicação
define('BASE_URL', 'http://localhost/unip');
define('UPLOAD_DIR', __DIR__ . '/../uploads/produtos/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif', 'webp']);

/**
 * Classe de Conexão com o Banco de Dados
 * 
 * Implementa o padrão Singleton para garantir uma única conexão
 * e usa PDO com prepared statements para prevenir SQL Injection.
 */
class Database {
    private static $instance = null;
    private $connection;
    
    /**
     * Construtor privado (Singleton)
     */
    private function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            
            $options = [
                // Retorna erros como exceções
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                // Retorna registros como arrays associativos
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                // Desabilita emulação de prepared statements (mais seguro)
                PDO::ATTR_EMULATE_PREPARES => false,
                // Define timeout de conexão
                PDO::ATTR_TIMEOUT => 5,
                // Usa conexões persistentes para melhor performance
                PDO::ATTR_PERSISTENT => false
            ];
            
            $this->connection = new PDO($dsn, DB_USER, DB_PASS, $options);
            
        } catch (PDOException $e) {
            // Em produção, registre o erro em um arquivo de log
            error_log("Erro de conexão com banco de dados: " . $e->getMessage());
            die("Erro ao conectar com o banco de dados. Por favor, tente novamente mais tarde.");
        }
    }
    
    /**
     * Obtém a instância única da conexão (Singleton)
     * 
     * @return Database Instância única
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Retorna a conexão PDO
     * 
     * @return PDO Objeto de conexão
     */
    public function getConnection() {
        return $this->connection;
    }
    
    /**
     * Previne clonagem da instância
     */
    private function __clone() {}
    
    /**
     * Previne desserialização da instância
     */
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}

/**
 * Função auxiliar para obter conexão
 * 
 * @return PDO Objeto de conexão
 */
function getConnection() {
    return Database::getInstance()->getConnection();
}
