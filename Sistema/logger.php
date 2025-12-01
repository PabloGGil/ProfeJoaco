<?php
$path_cli=__DIR__.'/../';
define ('ENTORNO','Desarrollo');
include_once($path_cli."Sistema/logger.php");

class Logger {
    private $log_file;
    private $enabled;
 	
    public function __construct($log_file = 'app.log', $enabled = true) {
         $conf= CargaConfiguracion::getInstance('');
        //  var_dump($conf);
         $this->log_file=__DIR__.'/../' .$conf->leeParametro("logfile");
        // $this->log_file = $log_file;
        $this->enabled = $enabled;
        echo $this->log_file;
    }
    
    public function log($message, $level = 'INFO', $context = []) {
        if (!$this->enabled) return;
        
        $timestamp = date('Y-m-d H:i:s');
        $log_message = "[$timestamp] [$level] $message";
        
        if (!empty($context)) {
            $log_message .= " " . json_encode($context);
        }
        
        $log_message .= PHP_EOL;
        
        file_put_contents($this->log_file, $log_message, FILE_APPEND | LOCK_EX);
    }
    
    public function error($message, $context = []) {
        $this->log($message, 'ERROR', $context);
    }
    
    public function info($message, $context = []) {
        $this->log($message, 'INFO', $context);
    }
    
    public function debug($message, $context = []) {
        $this->log($message, 'DEBUG', $context);
    }
}

// Manejo global de errores
function errorHandler($errno, $errstr, $errfile, $errline) {
    $logger = new Logger();
    $logger->error("PHP Error: $errstr", [
        'file' => $errfile,
        'line' => $errline,
        'errno' => $errno
    ]);
    
    // En producción, no mostrar detalles al usuario
    if (ENTORNO === 'production') {
        echo json_encode(['error' => 'Ocurrió un error interno']);
    } else {
        echo json_encode([
            'error' => $errstr,
            'file' => $errfile,
            'line' => $errline
        ]);
    }
    
    exit;
}

set_error_handler('errorHandler');

// Manejo de excepciones
function exceptionHandler($exception) {
    $logger = new Logger();
    $logger->error("Uncaught Exception: " . $exception->getMessage(), [
        'file' => $exception->getFile(),
        'line' => $exception->getLine(),
        'trace' => $exception->getTraceAsString()
    ]);
    
    http_response_code(500);
    echo json_encode(['error' => 'Error interno del servidor']);
    exit;
}

set_exception_handler('exceptionHandler');
?>
