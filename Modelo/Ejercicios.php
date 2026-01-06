<?php
$path_cli=__DIR__.'/../';
include_once($path_cli."BaseDatos/Base.php");

class Ejercicios{

    private $INSERT="INSERT INTO joacosch.ejercicios(
	id, musculo, ejercicio, explicacion)
	VALUES (?, ?, ?, ?)";
    public $musculo;
    public $ejercicio;
    public $explicacion;
    public $id;
    
    private $campos;
    
     
    
    public function __construct(){
        $this->campos = ['musculo','ejercicio', 'explicacion'];
      
    }
    public function ListarTodos(){
        try {
                $base=new BD();
               
                $resultado=$base->query("select * from joacosch.ejercicios");
                
           
                return  $resultado;
        } catch (Exception $e) {
            return new Respuesta(false, null, "DB_ERROR", $e->getMessage());
        }
    }

    public function BuscarPorId($id){

    }

    public function Crear(array $data){
        try     
        {
            $base=new BD();  
            $resultado=$base->Insert("joacosch.ejercicios",$data);
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
            
            $resultado=$base->Update("joacosch.ejercicios",$datos,$condicion);
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
            $resultado=$base->Delete("joacosch.ejercicios",$id);
            return  new Respuesta(true,"","","");
            // return  $resultado;
        } catch (Exception $e) {
            return new Respuesta(false, null, "DB_ERROR", $e->getMessage());
        }
    }

    public function getPlanes(){

    }

}

// $ej=new Ejercicios();
// var_dump($ej->ListarTodos());