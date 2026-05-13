<?php

$path_cli=__DIR__.'/../';
include_once($path_cli."BaseDatos/Conexion.php");
include_once($path_cli."Sistema/logger.php");
include_once($path_cli."Sistema/CargaConfiguracion.php");
include_once($path_cli."Sistema/Respuesta.php");


class BD {

	private $conn;
	private $db_host;
	private $db_port;
	private $db_name;
	private $db_user;
	private $db_pass;
	private $db_charset;
	private $dato;

	private $ROOT_PATH;
	
	private $sqlError;
	protected $Log;
	private $cantReg;
        
	// Variable para manejar transacciones
	private $inTransaction = false;

	public function __construct($dato=0){
		$this->Log=new Logger();
		BD::setRootPath();
		$conf= CargaConfiguracion::getInstance('');
	
		$this->db_host=$conf->leeParametro("host");
		$this->db_port=$conf->leeParametro("port");
		$this->db_name=$conf->leeParametro("name");
		$this->db_user=$conf->leeParametro("user");
		$this->db_pass=$conf->leeParametro("dbpass");
		$this->db_charset=$conf->leeParametro("charset");
		
		// Establecer charset por defecto si no está configurado
		if (empty($this->db_charset)) {
			$this->db_charset = 'utf8mb4';
		}
		
		$ROOT_PATH = BD::getRootPath();
		
		// Crear conexión MySQL (asumiendo que la clase DB_mysql_connection existe)
		$oconn=DB_mysql_connection::getInstance($ROOT_PATH, $this->db_host, $this->db_port, $this->db_name, $this->db_user, $this->db_pass);
		
		$this->conn=$oconn->getConector();
		
		// Configurar charset
		mysqli_set_charset($this->conn, $this->db_charset);
		
		if ($dato!=0){
			BD::seteaDato($dato);
		}
		else{
			$this->dato=array(0);
		}
		
		$this->cantReg=0;
	}

	protected function getRootPath(){
		return $this->ROOT_PATH;
	}

	protected function setRootPath(){
		$this->ROOT_PATH=$_SERVER['DOCUMENT_ROOT'];
	}

	protected function seteaDato($dato){
		$this->dato=$dato;
	}
	
	public function getCantReg(){
		return $this->cantReg;
	}

	/**
	 * Escapa y sanitiza un valor para MySQL
	 */
	private function escapeValue($value) {
		if ($value === null) return 'NULL';
		if (is_bool($value)) return $value ? '1' : '0';
		if (is_int($value)) return $value;
		if (is_float($value)) return str_replace(',', '.', (string)$value);
		return "'" . mysqli_real_escape_string($this->conn, $value) . "'";
	}

	/**
	 * Ejecuta una consulta SQL simple (modo legacy)
	 */
	public function execSql($strSQL)
	{
		try{
			if (!isset($strSQL)) {
				exit;
			}
			
			$result = mysqli_query($this->conn, $strSQL);
			
			if (!$result) {
				$error = mysqli_error($this->conn);
				$this->Log->error($error, $strSQL);
				return new Respuesta(false, null, "DB_ERROR", $error);
			}
			
			// Para consultas SELECT
			if (is_object($result)) {
				$data = mysqli_fetch_all($result, MYSQLI_ASSOC);
				$cuenta = count($data);
				mysqli_free_result($result);
			} else {
				// Para INSERT, UPDATE, DELETE
				$data = null;
				$cuenta = mysqli_affected_rows($this->conn);
			}
			
			$_array['cuenta'] = $cuenta;
			return new Respuesta(true, $data, "", "");			
		} catch(Exception $e){
			return new Respuesta(false, null, "DB_ERROR", $e->getMessage());
		}
	}

	/**
	 * Ejecuta una consulta preparada de forma segura
	 */
	private function executePrepared(string $sql, array $params) {
		// Reemplazar placeholders ($1, $2, etc.) por ? para MySQL
		$mysqlSql = preg_replace('/\$\d+/', '?', $sql);
		
		$stmt = mysqli_prepare($this->conn, $mysqlSql);
		if (!$stmt) {
			throw new Exception(mysqli_error($this->conn));
		}
		
		// Determinar tipos de parámetros
		$types = '';
		$bindParams = [];
		$bindParams[] = &$types;
		
		foreach ($params as $param) {
			if ($param === null) {
				$types .= 's';
				$bindParams[] = null;
			} elseif (is_int($param)) {
				$types .= 'i';
				$bindParams[] = $param;
			} elseif (is_float($param)) {
				$types .= 'd';
				$bindParams[] = $param;
			} elseif (is_bool($param)) {
				$types .= 'i';
				$bindParams[] = $param ? 1 : 0;
			} else {
				$types .= 's';
				$bindParams[] = $param;
			}
		}
		
		// Bind parameters usando call_user_func_array
		if (count($bindParams) > 1) {
			call_user_func_array([$stmt, 'bind_param'], $bindParams);
		}
		
		$stmt->execute();
		
		// Obtener resultado para consultas SELECT
		$result = $stmt->get_result();
		
		if ($result === false && $stmt->errno) {
			throw new Exception($stmt->error);
		}
		
		return ['stmt' => $stmt, 'result' => $result];
	}

	/**
	 * Método principal para ejecutar consultas con parámetros
	 */
	public function query(string $sql, array $params = []) {
		try {
			$startTime = microtime(true);
			$error = "";
			$exito = false;
			$data = null;
			
			// Si hay parámetros, usar consulta preparada
			if (!empty($params)) {
				$prepared = $this->executePrepared($sql, $params);
				$stmt = $prepared['stmt'];
				$result = $prepared['result'];
				
				if ($result && $result !== true) {
					// SELECT query
					$data = $result->fetch_all(MYSQLI_ASSOC);
					$exito = true;
					$this->cantReg = count($data);
					$result->free();
				} elseif ($stmt && $stmt->affected_rows >= 0) {
					// INSERT, UPDATE, DELETE
					$exito = true;
					$this->cantReg = $stmt->affected_rows;
				}
				
				$stmt->close();
			} else {
				// Consulta sin parámetros
				$result = mysqli_query($this->conn, $sql);
				
				if ($result === false) {
					$error = mysqli_error($this->conn);
					$exito = false;
				} else {
					$exito = true;
					
					if (is_object($result)) {
						// SELECT query
						$data = mysqli_fetch_all($result, MYSQLI_ASSOC);
						$this->cantReg = count($data);
						mysqli_free_result($result);
					} else {
						// INSERT, UPDATE, DELETE
						$this->cantReg = mysqli_affected_rows($this->conn);
					}
				}
			}
			
			$executionTime = microtime(true) - $startTime;
			
			if (!$exito) {
				if (empty($error)) {
					$error = mysqli_error($this->conn);
				}
				$this->Log->error("Error SQL", [
					'sql' => $sql,
					'params' => $params,
					'error' => $error,
					'time' => round($executionTime * 1000, 2) . 'ms'
				]);
			}
			
			// Log para consultas lentas
			if ($executionTime > 2.0) {
				$this->Log->info("Consulta lenta", [
					'sql' => $sql,
					'time' => round($executionTime * 1000, 2) . 'ms'
				]);
			}
			
			$resultado = new Respuesta($exito, $data, "", $error);
			return $resultado;
			
		} catch (Exception $e) {
			$this->Log->error("Error en consulta", [
				'sql' => $sql,
				'params' => $params,
				'exception' => $e->getMessage()
			]);
			throw $e;
		}
	}

	/**
	 * Insertar registro en tabla
	 */
	public function Insert(string $table, array $data): Respuesta {
		$columns = implode(', ', array_keys($data));
		$placeholders = '?' . str_repeat(', ?', count($data) - 1);
		
		$sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
		
		$result = $this->query($sql, array_values($data));
		
		// Agregar el ID insertado si fue exitoso
		if ($result->success) {
			$insertId = mysqli_insert_id($this->conn);
			$result->data = ['id' => $insertId];
		}
		
		return $result;
	}

	/**
	 * Actualizar registros en tabla
	 */
	public function Update(string $table, array $data, array $where): Respuesta {
		$setParts = [];
		$params = [];
		
		foreach ($data as $column => $value) {
			$setParts[] = "{$column} = ?";
			$params[] = $value;
		}
		
		$setClause = implode(', ', $setParts);
		
		$whereParts = [];
		foreach ($where as $column => $value) {
			$whereParts[] = "{$column} = ?";
			$params[] = $value;
		}
		
		$whereClause = implode(' AND ', $whereParts);
		
		$sql = "UPDATE {$table} SET {$setClause} WHERE {$whereClause}";
		
		$result = $this->query($sql, $params);
		return $result;
	}

	/**
	 * Eliminar registros de tabla
	 */
	public function Delete(string $table, array $id): Respuesta {
		$whereParts = [];
		$params = [];
		
		foreach ($id as $campo => $valor) {
			$whereParts[] = "{$campo} = ?";
			$params[] = $valor;
		}
		
		$whereClause = implode(' AND ', $whereParts);
		$sql = "DELETE FROM {$table} WHERE {$whereClause}";
		
		$result = $this->query($sql, $params);
		return $result;
	}

	/**
	 * Iniciar una transacción
	 */
	public function beginTransaction(): bool {
		if (!$this->inTransaction) {
			$this->inTransaction = mysqli_begin_transaction($this->conn);
			return $this->inTransaction;
		}
		return false;
	}

	/**
	 * Confirmar transacción
	 */
	public function commit(): bool {
		if ($this->inTransaction) {
			$result = mysqli_commit($this->conn);
			$this->inTransaction = !$result;
			return $result;
		}
		return false;
	}

	/**
	 * Revertir transacción
	 */
	public function rollback(): bool {
		if ($this->inTransaction) {
			$result = mysqli_rollback($this->conn);
			$this->inTransaction = !$result;
			return $result;
		}
		return false;
	}

	/**
	 * Obtener el último ID insertado
	 */
	public function lastInsertId(): int {
		return mysqli_insert_id($this->conn);
	}

	/**
	 * Escapar string para MySQL
	 */
	public function escapeString(string $string): string {
		return mysqli_real_escape_string($this->conn, $string);
	}

	/**
	 * Cerrar la conexión
	 */
	public function close(): void {
		if ($this->conn) {
			mysqli_close($this->conn);
		}
	}

	/**
	 * Destructor - cierra la conexión
	 */
	public function __destruct() {
		$this->close();
	}
}
	
/**
 * Clase para manejar resultados de consultas MySQL
 */
class DatabaseResult {
    private $result;
    
    public function __construct($result) {
        $this->result = $result;
    }
    
    public function fetchAll(): array {
        if (!$this->result) return [];
        $arr = mysqli_fetch_all($this->result, MYSQLI_ASSOC) ?: [];
        return $arr;
    }
    
    public function fetchAssoc(): ?array {
        if (!$this->result) return null;
        $row = mysqli_fetch_assoc($this->result);
        return $row ?: null;
    }
    
    public function fetchColumn(int $column = 0): array {
        if (!$this->result) return [];
        $rows = [];
        while ($row = mysqli_fetch_row($this->result)) {
            $rows[] = $row[$column] ?? null;
        }
        return $rows;
    }
    
    public function count(): int {
        if (!$this->result) return 0;
        return mysqli_num_rows($this->result);
    }
    
    public function free(): void {
        if ($this->result) {
            mysqli_free_result($this->result);
        }
    }
}

class DatabaseException extends Exception {}

// Fin clase BD