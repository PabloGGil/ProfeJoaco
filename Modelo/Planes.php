<?php
$path_cli=__DIR__.'/../';
include_once($path_cli."BaseDatos/Base.php");
class Planes{

 private $INSERT="INSERT INTO joacosch.planes(id, nombre, descripcion, id_ejercicio)	VALUES (?, ?, ?, ?)";
    public $nombre;
    public $descripcion;
    public $id_musculo;
    public $id;
    
    private $campos;

    
 public function __construct(){
        $this->campos = ['nombre', 'descripcion','id_musculo'];
      
    }
    public function ListarTodos(){
        try {
                $base=new BD();
               
                // $resultado=$base->query("select * from joacosch.planes");
                 $resultado=$base->query("select p.id, p.nombre, p.descripcion, e.musculo, e.ejercicio from joacosch.planes p, joacosch.ejercicios e
                                          where p.id_ejercicio=e.id");
           
                return  $resultado;
        } catch (Exception $e) {
            return new Respuesta(false, null, "DB_ERROR", $e->getMessage());
        }
    }

    public function BuscarPorId($id){

    }

    public function CrearPlan($datos){

    }

    public function ActualizarPlan($id, $datos){

    }

    public function EliminarPlan($id){

    }

    public function getUsuarios(){
        
    }
}