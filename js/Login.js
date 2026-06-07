import { PostData, getData } from './Api.js';
import { usuarioConfig } from './Config/UsuariosConfig.js';

const btnLogin=document.getElementById('btn-login');
const btnCambioPwd=document.getElementById('btn-cambio-passwd');
const formCambioPasswd=document.getElementById('formCambioPasswd')==null?false:true;




//  function MostrarMenuAdmin(estado ){
//     const adminSubmenu = document.getElementById('admin-submenu');
//     const menuAdmin = document.getElementById('menu-admin');
//     if (adminSubmenu) {
        
//             adminSubmenu.display = 'none';
//         }
//     else {
//         menuAdmin.innerHTML=`<li><a href="../Vista/AdminUsuario.php" id="nav-list">Usuarios</a></li>
//                             <li><a href="../Vista/AdminEjercicios.php" id="nav-list">Ejercicios</a></li>
//                             <li><a href="../Vista/AdminPlanes.php" id="nav-list">Planes</a></li>`
//     }
// }
if(formCambioPasswd){
    btnCambioPwd.addEventListener('click',async function(e) {
        e.preventDefault();   
        const passwd=document.getElementById('passwd').value;
        const repasswd = document.getElementById('re-passwd').value;
        const usuario=document.getElementById('user-name').textContent;
        if(passwd!=repasswd){
            alert("Las contraseñas no coinciden");
            return;
        }
        // Encriptar antes de enviar
        const secretKey = 'pp234';
        const passwordEnc = CryptoJS.AES.encrypt(passwd, secretKey).toString();
        const datos = {
            usuario: usuario,
            password: passwordEnc,
        };
        const rta=await PostData(usuarioConfig.endpoints.cambioPWD,datos);
        
        if (rta.success) {
            alert("cambio exitoso");
            window.location.href = '../index.php';
        }
        else{
            alert("No se pudo cambiar la contraseña");
        }
        window.location.href = '../index.php';
    });
}else{
    btnLogin.addEventListener('click',async function(e) {
        e.preventDefault();   
        const passwd=document.getElementById('password').value;
        const usuario = document.getElementById('correo').value;

        // Encriptar antes de enviar
        const secretKey = 'pp234';
        const passwordEnc = CryptoJS.AES.encrypt(passwd, secretKey).toString();
        const datos = {
            usuario: usuario,
            password: passwordEnc,
        };
        const rta=await PostData(usuarioConfig.endpoints.login,datos);
        
        if (rta.loginOK) {
            alert("Acceso exitoso");
            window.location.href = '../index.php';
        }
        else{
            alert("usuario o contraseña incorrectos")
        }
        window.location.href = '../index.php';
    });
}