<?php
// session_start();

// if (!isset($_SESSION['user'])) {
     
// 	header("Location: ../index.php");
//  }
// $estaversion = "2.8";
?>
   <!-- Inicio del  header-->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profe Joaco</title>
     
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome para íconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Select2 para búsqueda avanzada -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

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
                        <li><a href="AdminPlanes.php" id="nav-list">Planes</a></li>
                    </ul>
                </li>
        
            </ul>
        </div>
    </nav>
   <!-- fin del header.php-->
    