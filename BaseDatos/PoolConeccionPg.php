<?php

include_once($path_cli."BaseDatos/Class.ConfigDB.php");

class DatabaseConnectionPool {
    private static $instance = null;
    private $connections = [];
    private $config;
    
    private function __construct(Config $config) {
        $this->config = $config;
    }
    
    public static function getInstance(Config $config = null): self {
        if (self::$instance === null && $config !== null) {
            self::$instance = new self($config);
        }
        return self::$instance;
    }
    
    public function getConnection(string $name = 'default') {
        if (!isset($this->connections[$name])) {
            $this->connections[$name] = $this->createConnection();
        }
        
        // Verificar si la conexión sigue activa
        if (!pg_ping($this->connections[$name])) {
            $this->connections[$name] = $this->createConnection();
        }
        
        return $this->connections[$name];
    }
    
    private function createConnection() {
        $connectionString = sprintf(
            "host=%s port=%s dbname=%s user=%s password=%s",
            $this->config['db_host'],
            $this->config['db_port'],
            $this->config['db_name'],
            $this->config['db_user'],
            $this->config['db_pass']
        );
        
        $connection = pg_connect($connectionString, PGSQL_CONNECT_FORCE_NEW);
        
        if (!$connection) {
            throw new RuntimeException("No se pudo establecer conexión con la base de datos");
        }
        
        // Configuraciones adicionales
        pg_query($connection, "SET TIME ZONE 'UTC'");
        pg_query($connection, "SET client_encoding TO 'UTF8'");
        
        return $connection;
    }
    
    public function closeAll(): void {
        foreach ($this->connections as $connection) {
            pg_close($connection);
        }
        $this->connections = [];
    }
}
?>