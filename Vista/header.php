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

   <!-- Inicio del  header-->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profe Joaco</title>
     
    <!-- Bootstrap 5 CSS -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <!-- Font Awesome para íconos -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> -->
    <!-- Select2 para búsqueda avanzada -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> -->

    <link rel="stylesheet" href="../css/estilosOptimizados.css">
    <link rel="icon" type="image/x-icon" href="/favicon.png">
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>

</head>
<body>
    <header class="main-header">
        <div class="header-container">
             <div class="header-left"></div>
            <div class="logo-area">
                <h1>Profe Joaquin</h1>
                <p>Entrenamiento personalizado</p>
           </div>
        <div class="user-menu-container">
            <div class="user-menu">
                <div class="user-avatar">
                    <?php if($estaLogueado): ?>
                        <!-- Avatar cuando está logueado -->
                        <div class="avatar-circle">
                            <?php if(isset($_SESSION['avatar']) && !empty($_SESSION['avatar'])): ?>
                                <img src="<?php echo htmlspecialchars($_SESSION['avatar']); ?>" alt="Avatar">
                            <?php else: ?>
                                <!-- Mostrar iniciales del usuario -->
                                <?php 
                                    $iniciales = '';
                                    $nombreCompleto = $userName;
                                    $palabras = explode(' ', $nombreCompleto);
                                    if(count($palabras) >= 2) {
                                        $iniciales = strtoupper(substr($palabras[0], 0, 1) . substr($palabras[1], 0, 1));
                                    } else {
                                        $iniciales = strtoupper(substr($nombreCompleto, 0, 2));
                                    }
                                ?>
                                <span><?php echo $iniciales; ?></span>
                            <?php endif; ?>
                        </div>
                        <span id="user-name" class="user-name"><?php echo htmlspecialchars($userName); ?></span>
                    <?php else: ?>
                        <!-- Avatar por defecto (no logueado) -->
                        <div class="avatar-circle default">
                            <i class="fas fa-user"></i>
                        </div>
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
                            
                            <!-- Opción para cambiar avatar (opcional) -->
                            <a href="../Vista/cambiar-avatar.php" class="dropdown-item">
                                <i class="fas fa-camera"></i> Cambiar Avatar
                            </a>

                            <!-- Opción para cambiar password  -->
                            <a href="../Vista/cambiar-passwd.php" class="dropdown-item">
                                <i class="fas fa-key"></i> Cambiar Contraseña
                            </a>
                            
                            <?php if($userRole == 'admin'): ?>
                                <div class="dropdown-divider"></div>
                                <a href="../Vista/AdminUsuario.php" class="dropdown-item">
                                    <i class="fas fa-users-cog"></i> Admin Usuarios
                                </a>
                                <a href="../Vista/AdminEjercicios.php" class="dropdown-item">
                                    <i class="fas fa-dumbbell"></i> Admin Ejercicios
                                </a>
                                <a href="../Vista/AdminPlanes.php" class="dropdown-item">
                                    <i class="fas fa-calendar-alt"></i> Admin Planes
                                </a>
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
    
    <nav>
        <div class="container">
            <ul class="menu">
                <li><a href="../index.php" >Inicio</a></li>
                <li><a href="../Vista/actividades.php">Actividades</a></li>
                <li><a href="../Vista/planesEntrenamiento.php">Planes de  Entrenamiento</a></li>
                <li><a href="../Vista/registro.php">Registrate</a></li>
                <li><a href="../Vista/login.php">Ingresar</a></li>
                <li class="has-submenu ">
                
                    
        
            </ul>
        </div>
    </nav>
   <!-- fin del header.php-->
    