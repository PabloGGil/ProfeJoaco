<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
error_log("=== HEADER.PHP ===");
error_log("Sesion actual: " . print_r($_SESSION, true));
// echo $exito;
if(isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'){
         echo json_encode($_SESSION['rol'] === 'admin');
}

$estaLogueado = isset($_SESSION['logueado']) && $_SESSION['logueado'] === true;
$userRole = $_SESSION['rol'] ?? 'anonimo';
$userName = $_SESSION['usuario'] ?? 'Invitado';

// Determinar qué mostrar en el menú
$showAdminMenu = ($estaLogueado && $userRole === 'admin');
?>
<script>
    
    // Pasar variable de PHP a JavaScript
    // window.isAdmin =json_decode
   
    console.log("admin: "+window.isAdmin);
</script>
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

    <link rel="stylesheet" href="../css/estilosOptimizado.css"></link>
    <link rel="icon" type="image/x-icon" href="/favicon.png">
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script

</head>
<body>
    <header class="main-header">
        <div class="header-container">
            <div class="logo-area">
                <h1>Profe Joaquin</h1>
                <p>Entrenamiento personalizado</p>
            </div>
        
    <div class="user-menu-container">
                <div class="user-menu">
                    <div class="user-avatar">
                        <i class="fas fa-user-circle"></i>
                        <?php if($estaLogueado): ?>
                            <span class="user-name"><?php echo htmlspecialchars($userName); ?></span>
                        <?php else: ?>
                            <span class="user-name">Mi Cuenta</span>
                        <?php endif; ?>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    
                    <div class="dropdown-menu-custom">
                        <?php if($estaLogueado): ?>
                            <div class="user-info">
                                <strong><?php echo htmlspecialchars($userName); ?></strong>
                                <div class="user-email"><?php echo htmlspecialchars($userName); ?></div>
                            </div>
                            <div class="dropdown-divider"></div>
                            <a href="../Vista/perfil.php" class="dropdown-item">
                                <i class="fas fa-id-card"></i> Mi Perfil
                            </a>
                            <a href="../Vista/mis-entrenamientos.php" class="dropdown-item">
                                <i class="fas fa-calendar-check"></i> Mis Entrenamientos
                            </a>
                            <?php if($userRole=='admin'): ?>
                                <!-- <a href="../Vista/admin/dashboard.php" class="dropdown-item">
                                    <i class="fas fa-tachometer-alt"></i> Dashboard Admin
                                </a> -->
                            <?php endif; ?>
                            <div class="dropdown-divider"></div>
                            <a href="../Vista/Logout.php" class="dropdown-item">
                                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                            </a>
                        <?php else: ?>
                            <a href="../Vista/login.php" class="dropdown-item">
                                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                            </a>
                            <a href="../Vista/registro.php" class="dropdown-item">
                                <i class="fas fa-user-plus"></i> Registrarse
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="../Vista/recuperar-password.php" class="dropdown-item">
                                <i class="fas fa-key"></i> ¿Olvidaste tu contraseña?
                            </a>
                        <?php endif; ?>
                    </div>
                        </div>
    </div>
    </div>
    </header>
    
    <nav>
        <div class="container">
            <ul class="menu">
                <li><a href="../index.php" >Inicio</a></li>
                <li><a href="../Vista/actividades.php">Actividades</a></li>
                <li><a href="../Vista/planesEntrenamiento.php">Planes de  Entrenamiento</a></li>
                <li><a href="../Vista/registro.php">Registrate</a></li>
                <li><a href="../Vista/login.php">Ingresar</a></li>
                <li class="has-submenu ">
                <?php if($showAdminMenu): ?>
                    <a href="#" id="admin-menu">Administracion</a>
                    <!-- Submenú (puede estar oculto inicialmente con CSS) -->
                    <ul class=" submenu" >
                    <div id="menu-admin">
                        
                            <li><a href="../Vista/AdminUsuario.php" id="nav-list">Usuarios</a></li>
                            <li><a href="../Vista/AdminEjercicios.php" id="nav-list">Ejercicios</a></li>
                            <li><a href="../Vista/AdminPlanes.php" id="nav-list">Planes</a></li>
                        <?php endif ; ?>
                    </div>
                    </ul>
                    
                </li>
                    
        
            </ul>
        </div>
    </nav>
   <!-- fin del header.php-->
    