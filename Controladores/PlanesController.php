<?php
$path_cli=__DIR__.'/../';
include_once($path_cli."Sistema/Respuesta.php");
include_once($path_cli."modelo/planes.php");

class PlanesController{

    public function Index(){
        $planes=new Planes();
        $ret = $planes->ListarTodos();
        if(!$ret->success){
            $ret=new Respuesta(false, null, "DB_ERROR", "No se pudo acceder a los planes");        
        }
        $planes = [];
        foreach ($ret->data as $row) {
            $indicePlan = array_search($row['pnombre'], array_column($planes, 'pnombre'));
            if ($indicePlan!== false ) {
            // 2. Si existe, solo agregamos el nuevo ejercicio al array de 'ejercicios' de ese plan
            $planes[$indicePlan]['ejercicios'][] = [
                'grupoMuscular'=> $row['gm'],
                'nombre'       => $row['nombre'],
                'series'       => $row['series'],
                'repeticiones' => $row['repeticiones'],
                'peso'         => $row['peso']
                ];
            } else{
                $planes[] = $this->CargarFila($row);
            
            }  
        }
        $ret=new Respuesta($ret->success,$planes,$ret->errorCode,$ret->errorMessage);
        return $ret;
    }

    public function CargarFila($row){
        $fila=[
            'id'=>$row['id'],   
            'pnombre' => $row['pnombre'],
            'descripcion' => $row['descripcion'],
            'ejercicios' => [
                [
                'nombre'=>$row['nombre'],
                'grupoMuscular'=> $row['gm'],
                'series'=> $row['series'],
                'repeticiones'=>$row['repeticiones'],
                'peso'=>$row['peso']
                ]
            ]
        ];
        return $fila;
    }
    public function Mostrar($id){

    }

    public function Crear( $request){
       $plan=new Planes();
        // unset($data['q']);
        $pl= new pl();
            $pl->nombre=$request["nombre"];
            $pl->descripcion=$request["descripcion"];
            // $id_ejercicio=0;
            // $series=0;
            // $repeticiones=0;
            // $peso=0;
       
     $listaej=$request["ejercicios"];

        foreach($listaej as $ej){
            $pl->id_ejercicio=intval($ej["id"]);
            $pl->series=intval($ej["series"]);
            $pl->repeticiones=intval($ej["repeticiones"]);
            $pl->peso=intval($ej["peso"]);
            $ret = $plan->CrearPlan((array)$pl);
            // $ret = $plan->CrearPlan($data);
        }
        
        return $ret;
    }

    public function Actualizar($id, $request){

    }

    public function Eliminar($id){

    }

    public function getUsuarios($id_plan){

    }
}

class pl{
    public $nombre;
    public $descripcion;
    public $enombre;
    public int $id_ejercicio;
    public int $series;
    public int $repeticiones;
    public int $peso;
};

