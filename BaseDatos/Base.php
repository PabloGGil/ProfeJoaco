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

	private $textoSql;
	private $ROOT_PATH;
	
	private $sqlError;
	protected $Log;
	private $cantReg;
        

	public function __construct($dato=0){
		//verifica la existencia del archivo de configuraci�n y el path relativo del mismo para efectuar la inclucion
		$this->Log=new Logger();
		BD::setRootPath();
		$conf= CargaConfiguracion::getInstance('');
	
		$this->db_host=$conf->leeParametro("host");
		
		$this->db_port=$conf->leeParametro("port");
		$this->db_name=$conf->leeParametro("name");
		$this->db_user=$conf->leeParametro("user");
		$this->db_pass=$conf->leeParametro("dbpass");
		$this->db_charset=$conf->leeParametro("charset");
		$ROOT_PATH = BD::getRootPath();
		
		$oconn=DB_pg_connection::getInstance($ROOT_PATH,$this->db_host,$this->db_port,$this->db_name,$this->db_user,$this->db_pass);
		
		$this->conn=$oconn->getConector();
		
		if ($dato!=0){
			BD::seteaDato($dato);
		}
		else{
			$this->dato=array(0);
		}
		// var_dump($this->conn);
		
		$this->cantReg=0;
	}

	public function get_SqlError()
	{
		return $this ->sqlError;
	}
	public function set_SqlError($str)
	{
		$this ->sqlError=$str;
	}

	public function get_textoSQL()
	{
		return $this->textoSql;
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
	public  function getCantReg(){
		return $this->cantReg;
	}

	
	public function execSql($strSQL)
	{
		try{
			if (!isset($strSQL))
			{
				exit;
			}
			$res1=pg_send_query($this->conn,$strSQL);
			$resSql = pg_get_result($this->conn);
			// $this->set_sqlError( pg_result_error($resSql));
			// $this->set_sqlError(preg_replace('/[\x00-\x1F\x80-\xFF\x5E]/', '', $this->sqlError));
			$error=pg_result_error($resSql);
			if($error!="")
			{
				
				$this->Log->error( $error,$strSQL);
				// $_array['rc']="2";
				// $_array['msgerror']=$error;
				return new Respuesta(false, null, "DB_ERROR", "mal");
			}
			$_array['cuenta']=pg_num_rows($resSql);
			return new Respuesta(true, pg_fetch_all($resSql, PGSQL_ASSOC),"","");			
		}catch(Exception $e){
			return new Respuesta(false, null, "DB_ERROR", $e->getMessage());
		}
	
	}
private function executePrepared(string $sql, array $params) {
        // Escapar parámetros para prevenir SQL injection
        $escapedParams = array_map(function($param) {
            if ($param === null) return 'NULL';
            if (is_bool($param)) return $param ? 'TRUE' : 'FALSE';
            return pg_escape_literal($this->conn, $param);
        }, $params);
        
        // Reemplazar placeholders
        $query = $sql;
        $i = 1;
        foreach ($escapedParams as $param) {
            $query = preg_replace('/\$\d+/', $param, $query, 1);
            $i++;
        }
        
        return pg_query($this->conn, $query);
    }

	public function query(string $sql, array $params = []) {
        try {
            $startTime = microtime(true);
            $result=false;
			$error="";
            // Si hay parámetros, usar consulta preparada
            if (!empty($params)) {
                $result = $this->executePrepared($sql, $params);
            } else {
                $result = pg_query($this->conn, $sql);
            }
            
            $executionTime = microtime(true) - $startTime;
            
            if ($result === false) {
                $error = pg_last_error($this->conn);
                $this->Log->error("Error SQL", [
                    'sql' => $sql,
                    'params' => $params,
                    'error' => $error,
                    'time' => round($executionTime * 1000, 2) . 'ms'
                ]);
                $exito=false;
                throw new DatabaseException($error);
            }else{
				$exito=true;
			}
            
            $this->cantReg = pg_affected_rows($result) ?: pg_num_rows($result);
            
            // Log para consultas lentas
            if ($executionTime > 2.0) { // Más de 1 segundo
                $this->Log->info("Consulta lenta", [
                    'sql' => $sql,
                    'time' => round($executionTime * 1000, 2) . 'ms'
                ]);
            }
			
			$arrResult= new DatabaseResult($result);
			$resultado=new Respuesta($exito,$arrResult->fetchAll(),"",$error);
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

			
	public function Insert(string $table, array $data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = '$' . implode(', $', range(1, count($data)));
        
        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders}) RETURNING id";
        
        $result = $this->query($sql, array_values($data));
        
        // $arrResult= new DatabaseResult($result);
        // $fila= $arrResult->fetchAll();
        return $result;
    }


	public function Update(string $table, array $data, array $where):Respuesta {
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
        
        $sql = "UPDATE {$table} SET {$setClause} WHERE {$whereClause} RETURNING *";
        
        $result = $this->query($sql, $params);
        return $result;
    }

	public function Delete(string $table, $id){
		$sql = "DELETE FROM {$table} WHERE ID={$id}";
        
        $result = $this->query($sql);
		return $result;
	}
}
	
class DatabaseResult {
    private $result;
    
    public function __construct($result) {
        $this->result = $result;
    }
    
    public function fetchAll(): array {
        $arr=pg_fetch_all($this->result) ?: [];
		return $arr;

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

// 	public function errX($res,$strsql)
// 	{
// 		if ($res['rc']!="0") {
			
// 			$dato['info']=$strsql;
// 			$dato['msg']="Error en instrucción ";
// 			$dato['rc']="2";
// 			$dato['msgerror']=$res['msgerror'];
//             $this->Log->error("COD_INSERT - ". $strsql,$dato);
// 		}
// 		else{
// 			$dato=$res;
// 		}
// 		return $dato;
// 	}
// }
//-------------------------------------------------------



	/**
	 * Reemplaza la identificacion de la tabla y los valores de la sentencia SQL pasada como parametro
	 * con los parametros pasados en la creacion del objeto como una tabla clave-valor ; donde la clave
	 * responde al nombre del campo en la tabla definido en el mapa
	 *
	 * @param string SQL
	 * @param array Variables que se reemplazan en  el String de SQL
	 * @return string SQL con los valores reemplazando las variables
	 */

	// protected function reemplazaParametro(){
	// 	$vector=func_get_arg(0);
	// 	$params=$this->dato;
	// 	$cant=sizeof($params);
	// 	$array_bind = array();
	// 	$accion=$vector[0];
	// 	if (sizeof($params)!=0){
	// 		$claves=array_keys($params);
	// 		foreach($claves as $clave){
	// 			$cnt=0;
	// 			$str="'#".strtolower($clave)."#'";
	// 			$accion=str_replace($str,":".strtolower($clave),$accion,$cnt);
	// 			if ($cnt <= 0) {
	// 				$str="#".strtolower($clave)."#";
	// 				$accion=str_replace($str,":".strtolower($clave),$accion,$cnt);
	// 			}
	// 			if ($cnt > 0)
	// 			$array_bind[":".strtolower($clave)]=$params[$clave];
	// 			//$accion=str_replace($str,$params[$clave],$accion);
	// 		}
	// 	}
	// 	return array($accion,$array_bind);
	// }


	/**
	 * Metodo generico que sirve para retornar el valor maximo de una coleccion. funciona en conjunto con la variable
	 *  definida $MAYORORDEN dentro del metodo de la clase especifica desde la que se invoca.
	 * El campo del maximo valor debe estar definido 'AS max'
	 *	@param lista de parametro necesarios para el metodo
	 * @return resultado del query
	 */
	// public function maximoPosicion(){
	// 	$parametro = func_get_args();
	// 	$resultado=$this->execSql($this->MAYORORDEN,$parametro);
	// 	if (!$resultado){
	// 		$this->onError("COD_COLECCION",$this->MAYORORDEN);
	// 	}
	// 	return $resultado;
	// }

	/**
	 * Metodo generico de Busqueda por el ID de la tabla, funciona en conjunto con la variable de
	 * FINDBYID definida dentro del metodo de la clase especifica desde la cual se invoca.
	 *
	 * @param lista de parametros necesarios para el SQL
	 * @return resultado del query
	 */
	// public function findById(){
	// 	$parametro = func_get_args();
	// 	$resultado=$this->execSql($this->FINDBYID,$parametro);
	// 	if (!$resultado){
	// 		echo "EXPLOTA\n";

	// 		$this->onError("COD_FIND","FIND BY ID ".$this->FINDBYID.  get_class($this)."****");
	// 	}
	// 	return $resultado;
	// }


 // Fin clase BD


