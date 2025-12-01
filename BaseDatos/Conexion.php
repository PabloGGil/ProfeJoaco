<?php

/**
 * Clase de Conexion contra la base de datos de la aplicacion
 * Utiliza patron Singleton para asegurar una unica conexion para toda 
 * aplicacion.
 */
class DB_pg_connection {

	private $conector;
	private static $_instance;
	
	public function __construct($_db_host,$_db_port,$_db_name,$_db_user,$_db_pass,$_charset="UTF8"){
		$_conector=pg_connect("host={$_db_host} port={$_db_port} dbname={$_db_name} user={$_db_user} password={$_db_pass} options='--client_encoding={$_charset}' options='--search_path={$_db_user}'");
		//$_conector=pg_connect("host={$_db_host} port={$_db_port} dbname={$_db_name} user={$_db_user} password='metro123456' options='--client_encoding={$_charset}' options='--search_path={$_db_user}'");
		$this->conector=$_conector;
	}
	
	static public function getInstance($_ROOTPATH,$_db_host="",$_db_port="",$_db_name="",$_db_user="",$_db_pass="",$_db_charset="UTF8"){
		if(is_null(self::$_instance)){
			self::$_instance = new self($_db_host,$_db_port,$_db_name,$_db_user,$_db_pass,$_db_charset);
		}
		return self::$_instance;
	}
	
	public function getConector(){
		return $this->conector;
	}
	
} // Fin Clase de Conexion con Postgres

