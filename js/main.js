
import { initAdmin }      from '/js/admin.js';
import {usuarioConfig}    from './Config/UsuariosConfig.js';
import {ejerciciosConfig} from './Config/EjerciciosConfig.js';
import {initPlanesModule} from './Modulos/ABMPlanes.js';
import { APP_ENV }        from './Config/ConfigEntorno.js';

const adminLink = document.getElementById('admin-menu');
const adminSubmenu = document.getElementById('admin-submenu');
const menuAdmin = document.getElementById('menu-admin');
// if (!window.isAdmin) {
//     adminLink.addEventListener('click', function(e) {
//         e.preventDefault();
//           alert('Acceso denegado, redirigiendo a login');
//           window.location.href = '../Vista/Login.php';
//         });
    
    
//     // Ocultar submenú para no admins
//     if (adminSubmenu) {
      
//         adminSubmenu.display = 'none';
//     }
// } else {
//       menuAdmin.innerHTML=`<li><a href="../Vista/AdminUsuario.php" id="nav-list">Usuarios</a></li>
//                         <li><a href="../Vista/AdminEjercicios.php" id="nav-list">Ejercicios</a></li>
//                         <li><a href="../Vista/AdminPlanes.php" id="nav-list">Planes</a></li>`
 

document.addEventListener('DOMContentLoaded', () => {
 

  const path = window.location.pathname;
  console.log("----- PATH: " +path);
  if (path.includes('Planes')) {
    // Para entidades simples, usar setupCrud directamente
    console.log("-----  llamo a la inicializacion de Planes" +path);
    initPlanesModule();
    // setupCrud(planesConfig);
  } 
  if (path.includes('Ejercicio')) {
    // Para entidades simples, usar setupCrud directamente
    console.log("-----  llamo a la inicializacion de Ejercicio: " +path);  
    initAdmin(ejerciciosConfig);
  }
  if (path.includes('Usuario')) {
    // Para entidades simples, usar setupCrud directamente
    console.log("----- llamo a la inicializacion de usuario: " +path);
    initAdmin(usuarioConfig);
  }
 }); 