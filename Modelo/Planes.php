<?php
$path_cli=__DIR__.'/../';
include_once($path_cli."BaseDatos/Base.php");
include_once($path_cli."Sistema/CargaConfiguracion.php");
include_once($path_cli."Sistema/logger.php");

class Planes{

//  private $INSERT="INSERT INTO joacosch.planes(id, nombre, descripcion, id_ejercicio, series, repeticiones, peso)	VALUES (?, ?, ?, ?, ?, ?, ?)";
    public $nombre;
    public $descripcion;
    public $id_ejercicio;
    public $series;
    public $repeticiones;
    public $peso;
    public $id;
    private $tipoBD;
    private $esquema;
    private $tabla="planes";
    private $tabEj="ejercicios";
    private $log;
    
    private $campos;

    
 public function __construct(){
        $this->log=new Logger();
        $this->campos = ['nombre', 'descripcion','id_musculo'];
        $conf= CargaConfiguracion::getInstance('');
		$this->tipoBD=$conf->leeParametro("tipobd");
		$this->esquema=$conf->leeParametro("schema");
        if($this->tipoBD=="postgres"){
            $this->tabla=$this->esquema .".".$this->tabla;
            $this->tabEj=$this->esquema .".".$this->tabEj;
        }
    }
    public function ListarTodos(){
        try {
                $base=new BD();
               $sql="select p.id, p.nombre as pnombre,p.id_ejercicio, p.descripcion,p.repeticiones,p.series, p.peso, e.grupo_muscular as gm, e.nombre from {$this->tabla} p, {$this->tabEj} e
                                          where p.id_ejercicio=e.id";
                // $resultado=$base->query("select * from joacosch.planes");
                 $resultado=$base->query($sql);
                 $this->log->log("data planes ","INFO",$resultado);
           
                return  $resultado;
        } catch (Exception $e) {
            $this->log->log("data planes ","MODELO_ERROR",$e->getMessage());
            return new Respuesta(false, null, "DB_ERROR", $e->getMessage());
        }
    }

    public function ExisteNombre($nombre){
        $existe=true;
        $base= new BD();
        $res=$base->query("select count(nombre) from {$this->tabla} where nombre='".$nombre."'");
        if($res->data[0]['count']=="0"){
            $existe= false;
        }
        return $existe;
    }
    public function BuscarPorId($id){

    }

    public function CrearPlan(array $datos){
        try     
        {
            $base=new BD();  
            $resultado=$base->Insert($this->tabla,$datos);
            return  new Respuesta(true,"","","");
            return  $resultado;
        } catch (Exception $e) {
            return new Respuesta( false , null, "Crear Plan - DB_ERROR", $e->getMessage());
        }
    }

    public function ActualizarPlan($id, $datos){

    }

    public function EliminarPlan($id){
        try     
        {
            $base=new BD();  
            $resultado=$base->Delete($this->tabla,$id);
            // return  new Respuesta(true,"","","");
            return  $resultado;
        } catch (Exception $e) {
            return new Respuesta( false , null, "Crear Plan - DB_ERROR", $e->getMessage());
        }
    }

    public function getId($nombre){
        $base=new BD();
        $resultado=$base->query("select id from {$this->tabla} where nombre='{$nombre}'");
        return $resultado;
    }
}

// $b=new Planes();
// print_r( $b->ExisteNombre(' full'));
// $b->ListarTodos();