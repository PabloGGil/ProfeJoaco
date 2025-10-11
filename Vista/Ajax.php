<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
$path_cli = __DIR__ . '/../../';
require_once $path_cli . "controller/class.ambienteBSN.php";
require_once $path_cli . "controller/class.CharsetBSN.php";
require_once $path_cli . "generic_class/class.GenericBSN.php";
require_once $path_cli . "model/class.Bitacora.php";
require_once $path_cli . "controller/class.StatusBSN.php";
require_once $path_cli . "controller/class.TipoBackupBSN.php";
require_once $path_cli . "controller/class.BackupBSN.php";
require_once $path_cli . "controller/class.TipoMotorBSN.php";
require_once $path_cli . "controller/class.MotorBSN.php";
require_once $path_cli . "controller/class.InstanciaBackupBSN.php";
require_once $path_cli . "controller/class.InstanciaBSN.php";
require_once $path_cli . "controller/class.InstanciaMotorBSN.php";
require_once $path_cli . "controller/class.InstanciaEsquemaBSN.php";
require_once $path_cli . "controller/class.AplicacionBSN.php";
require_once $path_cli . "controller/class.EsquemaBSN.php";
require_once $path_cli . "controller/class.serversBSN.php";
require_once $path_cli . "controller/class.Sys_groupBSN.php";
require_once $path_cli . "generic_class/class.Puertos.php";

$cambio=9;
session_start();

session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../../index.php");
}
//print_r($_POST);

if ($_POST['key'] === $_SESSION['key']) {
    print "User is " . $_SESSION['user'];
}

$json = file_get_contents('php://input');

// Decodifica el JSON a un array asociativo
$data = json_decode($json, true);
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}
$ret['rc'] = "1";
$ret['msg'] = 'Falto parametro de consulta';
$ret['info'] = array();
$ret['msgerror']="";
$retorno = array();
// $_SESSION['buffer']=array();

//$ret = "error";
if (isset($_GET['q'])) {
    switch ($_GET['q']) {
        case 'Registro':
            $instesq=new InstEsquema();
            $aux=['nombre' =>"ADF_MGPAP036"];
            $res=$instesq->Leer_BD($aux);
            break;
        case 'Planes':
            if (isset($_GET['g']))
            $ambBSN = new Sys_groupBSN();
            $ret = $ambBSN->get();
            $msg = 'Lista de Grupos del Sistema';
            break;
       case 'AdminPlanes':
        
            $ambBSN = new AD_contact();
            if (isset($_GET['g'])) {
                 $ret['info']=$ambBSN->buscargrupo($_GET['g']);
                 $msg = 'Grupos de AD que coinciden con'. $_GET['g'];
                 $ret['rc']="0";
                 $ret['msgerror']="";
            }
            break;
        case 'Usuarios':
            //$rc = 0;
            
            if(isset($_GET['id_motor']) && isset($_GET['t'])){
                
                $bkpBSN = new Puertos();
                if( strtolower($_GET['t'])=="o"){
                    $ret=$bkpBSN->getOcupados(strtolower($_GET['id_motor']));
                    $msg = 'Lista de puertos Ocupados';
                }else if(strtolower($_GET['t'])=="l"){
                    $ret=$bkpBSN->getLibres(strtolower($_GET['id_motor']));
                    $msg = 'Lista de puertos Libres';
                }

            }
            break;           
                                  
    }
    $cant=count($ret['info']);
    $retorno['rc'] = $ret['rc'];
    $retorno['msg'] = $msg ;//." - " . $objBSN->DiccionarioToTXT($ret);;
    $retorno['coleccion'] = $ret['info'];
    $retorno['msgerror']=$ret['msgerror'];
    if(isset($ret['extra'])){
        $retorno['extra']=$ret['extra'];
    }else{
        $retorno['extra']=0;
    }
    
    
} else {
    ////////////////////////////////////////////////////////////////////////
    ////////////////////////Inicio de recepcion json////////////////////////
    ////////////////////////////////////////////////////////////////////////
    $rc = '1';
    $msg = "error";
    $ret = array();
   

    if (! isset($_SESSION['user']))
        $user="NN";
    else
        $user=$_SESSION['user'];
    // decodifico la entrada en UTF8
    $params = json_decode(file_get_contents('php://input'), true, 512, JSON_UNESCAPED_UNICODE);
    //print_r($params['info']);
    switch ($params['q']) {
       
        case 'addBackup':
            $objBSN = new BackupBSN();
            $ret = $objBSN->Agregar_BSN($params['info']);
            if ($ret['rc']=="0") {
                $bitacora->addRegistro("Backup", $user, "Agregar", "", json_encode($params['info']));
                // $rc = 0;
                // $msg = "OK";
            }
            break;
        case 'modBackup':
            $objBSN = new BackupBSN();
           
            break;
        case 'delBackup':
            $objBSN = new BackupBSN();
            $ret = $objBSN->Borrar_BSN($params['info']);
            if ($ret['rc']=="0") {
                $bitacora->addRegistro("Backup", $user,"Borrar", json_encode($params['info']), "");
                // $rc = 0;
                // $msg = "OK";
            }
            break;
       
     }

    $retorno=$ret;
}
header('Content-Type: application/json');
http_response_code(200);
echo json_encode($retorno, JSON_UNESCAPED_UNICODE);
