<?php 
include "header.php" ;
// Si ya está logueado, redirigir
// if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
//     header("Location: index.php");
//     exit();
// }

// // Procesar login
// if($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $usuario = $_POST['usuario'] ?? '';
//     $password = $_POST['password'] ?? '';
    
//     // Validar contra tu base de datos
//     if($usuario === 'admin' && $password === '1234') {
//         $_SESSION['user_id'] = 1;
//         $_SESSION['user_name'] = $usuario;
//         $_SESSION['user_rol'] = 'admin';
//         $_SESSION['logged_in'] = true;
        
//         header("Location: index.php");
//         exit();
//     } else {
//         $error = "Usuario o contraseña incorrectos";
//     }
// }

// ?>
    <a href="../Vista/Logout.php"></a>
    <main>
        <div class="container">
            <div class="form-container">
                <h2>Ingreso</h2>
                <form id="userForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="firstName">Correo</label>
                            <input type="text" id="correo" name="correo" placeholder="Nombre de usuario/correo" required>
                        </div>
                        <div class="form-group">
                            <label for="lastName">Contraseña</label>
                            <input type="password" id="password" name="password" placeholder="Ingrese su contraseña" required>
                        </div>
                    </div>
                    
                                   
                    <button id='btn-login' class="btn btn-submit">Enviar</button>
                </form>
            </div>
        </div>
    </main>
    <script type='module' src='../js/Login.js'></script>
    
 <?php include "footer.php" ?>