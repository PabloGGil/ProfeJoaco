<?php

$path_cli=__DIR__.'/../';
include_once($path_cli."BaseDatos/Conexion.php");
include_once($path_cli."Sistema/logger.php");
include_once($path_cli."Sistema/CargaConfiguracion.php");
include_once($path_cli."Sistema/Respuesta.php");


/**
 * Ver 1.0 del 28-01-2011
 * Incluye registro de error de SQL en tabla de errores sqlerror, on fecha de error y texto del sql
 * Clase basica de trabajo con objetos de base de datos
 * utilizar por extencion.
 * Genera conectividad con la Base de datos
 *
 * Ver 1.2 del 11/07/2011
 * Incluye la lectura del parametro ROOT_PATH
 * 
 * Ver 1.3 del 08/10/2011
 * INcluye la actualizacion del registro en la tabla de log
 */

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
		var_dump($this->conn);
		
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
			return new Respuesta(true, pg_fetch_all($resSql, PGSQL_ASSOC));			
		}catch(Exception $e){
			return new Respuesta(false, null, "DB_ERROR", $e->getMessage());
		}
	
	}
}
	

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
//  $base=new BD();
//  $resultado=$base->execSql("select * from joacosch.ejercicios");
//  var_dump($resultado);


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


