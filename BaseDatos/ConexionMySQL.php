<?php

class DB_mysql_connection {

	private $conector;
	private static $_instance;
	
	public function __construct($_db_host, $_db_port, $_db_name, $_db_user, $_db_pass, $_charset = "UTF8"){
		try{
			// Convertir charset de PostgreSQL a MySQL (UTF8 -> utf8mb4)
			$mysql_charset = ($_charset == "UTF8") ? "utf8mb4" : $_charset;
			
			// Construir el host con puerto si es necesario
			$host = $_db_host;
			if (!empty($_db_port) && $_db_port != "3306") {
				$host = $_db_host . ":" . $_db_port;
			}
			
			// Crear conexión MySQL
			$_conector = mysqli_connect($host, $_db_user, $_db_pass, $_db_name);
			
			// Verificar conexión
			if (!$_conector) {
				throw new Exception("Error de conexión MySQL: " . mysqli_connect_error());
			}
			
			// Configurar charset
			mysqli_set_charset($_conector, $mysql_charset);
			
			// Configurar modo de errores (similar a PostgreSQL)
			mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
			
			$this->conector = $_conector;
			
		} catch(Exception $e){
			// Registrar error si es necesario
			error_log("Error en DB_mysql_connection: " . $e->getMessage());
			$this->conector = null;
		}
	}
	
	static public function getInstance($_ROOTPATH, $_db_host = "", $_db_port = "", $_db_name = "", $_db_user = "", $_db_pass = "", $_db_charset = "UTF8"){
		if(is_null(self::$_instance)){
			self::$_instance = new self($_db_host, $_db_port, $_db_name, $_db_user, $_db_pass, $_db_charset);
		}
		return self::$_instance;
	}
	
	public function getConector(){
		return $this->conector;
	}
	
	/**
	 * Verificar si la conexión está activa
	 */
	public function isConnected(): bool {
		return $this->conector !== null && mysqli_ping($this->conector);
	}
	
	/**
	 * Cerrar la conexión manualmente
	 */
	public function close(): void {
		if ($this->conector) {
			mysqli_close($this->conector);
			$this->conector = null;
		}
	}
	
	/**
	 * Destructor - cierra la conexión
	 */
	public function __destruct() {
		$this->close();
	}
}