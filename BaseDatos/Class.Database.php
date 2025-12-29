<?php

include_once($path_cli."Sistema/logger.php");
include_once($path_cli."Sistema/CargaConfiguracion.php");

class Database {
    private $connection;
    private $config;
    private $logger;
    private $rowCount = 0;
    private $ROOT_PATH;
    
    public function __construct() {
        Database::setRootPath();
        $conf= CargaConfiguracion::getInstance('');
        echo "constructor";
		$this->config['db_host']=$conf->leeParametro("host");
		
		$this->config['db_host']=$conf->leeParametro("port");
		$this->config['db_host']=$conf->leeParametro("name");
		$this->config['db_host']=$conf->leeParametro("user");
		$this->config['db_host']=$conf->leeParametro("dbpass");
		$this->config['db_host']=$conf->leeParametro("charset");
        // $this->config = $config;
        $this->logger = new Logger();
        $this->connect();
    }
    
    protected function setRootPath(){
		$this->ROOT_PATH=$_SERVER['DOCUMENT_ROOT'];
	}
    private function connect(): void {
        try {
            $dsn = sprintf(
                "host=%s port=%s dbname=%s user=%s password=%s",
                $this->config['db_host'],
                $this->config['db_port'],
                $this->config['db_name'],
                $this->config['db_user'],
                $this->config['db_pass']
            );
            
            $this->connection = pg_connect($dsn);
            
            if (!$this->connection) {
                throw new DatabaseException("No se pudo conectar a la base de datos");
            }
            
            // Configurar encoding
            pg_set_client_encoding($this->connection, $this->config->get('db_charset', 'UTF8'));
            
        } catch (Exception $e) {
            $this->logger->error("Error de conexión: " . $e->getMessage());
            throw $e;
        }
    }
    
    public function query(string $sql, array $params = []): DatabaseResult {
        try {
            $startTime = microtime(true);
            echo "query";
            // Si hay parámetros, usar consulta preparada
            if (!empty($params)) {
                $result = $this->executePrepared($sql, $params);
            } else {
                $result = pg_query($this->connection, $sql);
            }
            
            $executionTime = microtime(true) - $startTime;
            
            if ($result === false) {
                $error = pg_last_error($this->connection);
                $this->logger->error("Error SQL", [
                    'sql' => $sql,
                    'params' => $params,
                    'error' => $error,
                    'time' => round($executionTime * 1000, 2) . 'ms'
                ]);
                
                throw new DatabaseException($error);
            }
            
            $this->rowCount = pg_affected_rows($result) ?: pg_num_rows($result);
            
            // Log para consultas lentas
            if ($executionTime > 1.0) { // Más de 1 segundo
                $this->logger->info("Consulta lenta", [
                    'sql' => $sql,
                    'time' => round($executionTime * 1000, 2) . 'ms'
                ]);
            }
            
            return new DatabaseResult($result);
            
        } catch (Exception $e) {
            $this->logger->error("Error en consulta", [
                'sql' => $sql,
                'params' => $params,
                'exception' => $e->getMessage()
            ]);
            throw $e;
        }
    }
    
    private function executePrepared(string $sql, array $params) {
        // Escapar parámetros para prevenir SQL injection
        $escapedParams = array_map(function($param) {
            if ($param === null) return 'NULL';
            if (is_bool($param)) return $param ? 'TRUE' : 'FALSE';
            return pg_escape_literal($this->connection, $param);
        }, $params);
        
        // Reemplazar placeholders
        $query = $sql;
        $i = 1;
        foreach ($escapedParams as $param) {
            $query = preg_replace('/\$\d+/', $param, $query, 1);
            $i++;
        }
        
        return pg_query($this->connection, $query);
    }
    
    public function beginTransaction(): bool {
        return pg_query($this->connection, "BEGIN");
    }
    
    public function commit(): bool {
        return pg_query($this->connection, "COMMIT");
    }
    
    public function rollback(): bool {
        return pg_query($this->connection, "ROLLBACK");
    }
    
    public function insert(string $table, array $data): ?int {
        $columns = implode(', ', array_keys($data));
        $placeholders = '$' . implode(', $', range(1, count($data)));
        
        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders}) RETURNING id";
        
        $result = $this->query($sql, array_values($data));
        $row = $result->fetchAssoc();
        
        return $row['id'] ?? null;
    }
    
    public function update(string $table, array $data, array $where): int {
        $setParts = [];
        $i = 1;
        
        foreach ($data as $column => $value) {
            $setParts[] = "{$column} = \${$i}";
            $i++;
        }
        
        $setClause = implode(', ', $setParts);
        
        $whereParts = [];
        foreach ($where as $column => $value) {
            $whereParts[] = "{$column} = \${$i}";
            $i++;
        }
        
        $whereClause = implode(' AND ', $whereParts);
        $params = array_merge(array_values($data), array_values($where));
        
        $sql = "UPDATE {$table} SET {$setClause} WHERE {$whereClause}";
        
        $result = $this->query($sql, $params);
        return $this->rowCount;
    }
    
    public function getRowCount(): int {
        return $this->rowCount;
    }
    
    public function getLastError(): string {
        return pg_last_error($this->connection) ?: '';
    }
    
    public function __destruct() {
        if ($this->connection) {
            pg_close($this->connection);
        }
    }
}

class DatabaseResult {
    private $result;
    
    public function __construct($result) {
        $this->result = $result;
    }
    
    public function fetchAll(): array {
        return pg_fetch_all($this->result) ?: [];
    }
    
    public function fetchAssoc(): ?array {
        $row = pg_fetch_assoc($this->result);
        return $row ?: null;
    }
    
    public function fetchColumn(int $column = 0): array {
        $rows = [];
        while ($row = pg_fetch_row($this->result)) {
            $rows[] = $row[$column];
        }
        return $rows;
    }
    
    public function count(): int {
        return pg_num_rows($this->result);
    }
    
    public function free(): void {
        if ($this->result) {
            pg_free_result($this->result);
        }
    }
}

class DatabaseException extends Exception {}

$db=new Database();
echo "en el balde";
var_dump($db->query("select * from joacosch.ejercicios"));
?>
