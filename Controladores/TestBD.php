<?php
$path_cli=__DIR__.'/../';
// include_once($path_cli."BaseDatos/Base.php");
include_once($path_cli."Sistema/CargaConfiguracion.php");
class test{
    private $db_host;
	private $db_port;
	private $db_name;
	private $db_user;
	private $db_pass;
	private $db_charset;
    private $tipoBase;
    private $esquema;

    public function __construct() {
        $conf= CargaConfiguracion::getInstance('');    
        $this->db_host=$conf->leeParametro("host");
		
		$this->db_port=$conf->leeParametro("port");
		$this->db_name=$conf->leeParametro("name");
		$this->db_user=$conf->leeParametro("user");
		$this->db_pass=$conf->leeParametro("dbpass");
		$this->db_charset=$conf->leeParametro("charset");
        $this->tipoBase=$conf->leeParametro("tipobd");
        $this->esquema=$conf->leeParametro("schema");
    }
    public function testdirecto(){
        $conexion=null;
        $tabla="";
        if($this->tipoBase=='mysql'){
            $conexion = mysqli_connect($this->db_host, $this->db_user, $this->db_pass, $this->db_name);
            $tabla="ejercicios";
            if (!$conexion) {
                die("Error de conexión: " . mysqli_connect_error());
            }
            $resultado = mysqli_query($conexion, "SELECT * FROM {$tabla}");

            if ($resultado) {
                // Procesar resultados
                while ($fila = mysqli_fetch_assoc($resultado)) {
                    echo $fila['grupo_muscular'] . "<br>";
                }
            }
        }
        if($this->tipoBase=="postgres"){    
            $conexion = pg_connect("host='localhost' port='5433' dbname=profejoaco user=joaco password=joaco2002 options='--client_encoding=UTF8' options='--search_path=joaco'" );
            $tabla=$this->esquema .".ejercicios";
            $resultado = pg_query($conexion, "SELECT * FROM {$tabla}");

            if ($resultado) {
                // Procesar resultados
                while ($fila = pg_fetch_assoc($resultado)) {
                    echo $fila['grupo_muscular'] . "<br>";
                }
            }
        }
        // Verificar si la conexión fue exitosa
        // if (!$conexion) {
        //     die("Error de conexión: " . mysqli_connect_error());
        // }

        // Ahora sí podemos ejecutar consultas
        
    }

    public function testClasebd(){
       $bd=new BD();
        $resultado=$bd->query("SELECT * FROM ejercicios");
return $resultado;
        // if ($resultado.success) {
        //     // Procesar resultados
        //     while ($fila = mysqli_fetch_assoc($resultado)) {
        //         echo $fila['grupo_muscular'] . "<br>";
        //     }
        // }
    }

}
?>