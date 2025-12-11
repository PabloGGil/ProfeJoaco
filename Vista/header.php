<?php
// session_start();

// if (!isset($_SESSION['user'])) {
     
// 	header("Location: ../index.php");
//  }
// $estaversion = "2.8";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profe Joaco</title>
    <link rel="stylesheet" href="../css/estilos.css"></link>
</head>
<body>
    <header>
        <div class="container">
            <h1>Profe Joaquin</h1>
            <p>Entrenamiento personalizado</p>
        </div>
    </header>
    
    <nav>
        <div class="container">
            <ul class="menu">
                <li><a href="../index.php" >Inicio</a></li>
                <li><a href="actividades.php">Actividades</a></li>
                <li><a href="planesEntrenamiento.php">Planes de  Entrenamiento</a></li>
                <li><a href="registro.php">Registrate</a></li>
                <li><a href="login.php">Ingresar</a></li>
                <li class="has-submenu ">
                
                    <a href="#" id="usuarios-menu">Administracion</a>
                    <!-- Submenú (puede estar oculto inicialmente con CSS) -->
                    <ul class=" submenu">
                        <li><a href="AdminUsuario.php" id="nav-list">Usuarios</a></li>
                        <li><a href="AdminEjercicios.php" id="nav-list">Ejercicios</a></li>
                    </ul>
                </li>
        
            </ul>
        </div>
    </nav>
