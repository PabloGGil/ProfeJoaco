<?php
$path_cli=__DIR__.'/../';
include_once($path_cli."BaseDatos/Base.php");
include_once($path_cli."Sistema/CargaConfiguracion.php");
include_once($path_cli."Sistema/logger.php");
class Ejercicios{

    // private $INSERT="INSERT INTO joacosch.ejercicios(
	// id, musculo, ejercicio, explicacion)
	// VALUES (?, ?, ?, ?)";
    public $musculo;
    public $ejercicio;
    public $explicacion;
    public $id;
    private $tipoBD;
    private $esquema;
    private $tabla="ejercicios";
    private $campos;
    private $log;
    
     
    
    public function __construct(){
        $this->log=new logger();
        $this->campos = ['musculo','ejercicio', 'explicacion'];
        $conf= CargaConfiguracion::getInstance('');
		$this->tipoBD=$conf->leeParametro("tipobd");
		$this->esquema=$conf->leeParametro("schema");
        if($this->tipoBD=="postgres"){
            $this->tabla=$this->esquema .".".$this->tabla;
        }
       
    }
    public function ListarTodos(){
        try {
                $base=new BD();
               
                $resultado=$base->query("select * from {$this->tabla}");
                
           
                return  $resultado;
        } catch (Exception $e) {
            $this->log->log("Listar Todos- ejercicio","ERROR",$e->getMessage());
            return new Respuesta(false, null, "DB_ERROR", $e->getMessage());
        }
    }

    public function BuscarPorId($id){

    }

    public function Crear(array $data){
        try     
        {
            $base=new BD();  
            $resultado=$base->Insert($this->tabla,$data);
            return  new Respuesta(true,"","","");
            // return  $resultado;
        } catch (Exception $e) {
            return new Respuesta(false, null, "DB_ERROR", $e->getMessage());
        }
    }

    public function Actualizar(array $datos){
    try     
        {
            $base=new BD(); 
            $condicion['id']= $datos['id'];
            unset($datos['id']);
            
            $resultado=$base->Update($this->tabla,$datos,$condicion);
            return  $resultado;
            // return  $resultado;
        } catch (Exception $e) {
            return new Respuesta(false, null, "DB_ERROR", $e->getMessage());
        }
    }

    public function Eliminar( $id){
        try     
        {
            $base=new BD();  
            $resultado=$base->Delete($this->tabla,$id);
            return  new Respuesta(true,"",$resultado->errorCode,$resultado->errorMessage);
            
        } catch (Exception $e) {
            return new Respuesta(false, null, "ErrorModelo", $e->getMessage());
        }
    }

    public function getPlanes(){

    }

}

// $ej=new Ejercicios();
// var_dump($ej->ListarTodos());