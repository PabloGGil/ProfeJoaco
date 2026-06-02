import { PostData, getData } from './Api.js';
import { usuarioConfig } from './Config/UsuariosConfig.js';

const btnLogin=document.getElementById('btn-login');

btnLogin.addEventListener('click',async function(e) {
    e.preventDefault();   
    const passwd=document.getElementById('password').value;
    const usuario = document.getElementById('correo').value;

    // async function LoginUsuario() {

        
    // Encriptar antes de enviar
    const secretKey = 'pp234';
    const passwordEnc = CryptoJS.AES.encrypt(passwd, secretKey).toString();
    const datos = {
        usuario: usuario,
        password: passwordEnc,
    };
    const rta=await PostData(usuarioConfig.endpoints.login,datos);
    // 'acceso'=>true, 'rol'=>'admin']
    if (rta.loginOK) {
        alert("Acceso exitoso");
        // if(rta.rol=='admin'){
        //     window.isAdmin=true;    
        //     MostrarMenuAdmin(true);            
        // }else{
        //     window.isAdmin=false;
        // }
        // window.loggedIn=true;
        window.location.href = '../index.php';
    }
    else{
        // window.loggedIn=false;
        // window.isAdmin=false;
        
        alert("usuario o contraseña incorrectos")
    }

    console.log("Admin-----: " + window.isAdmin);
    window.location.href = '../index.php';
  
});

 function MostrarMenuAdmin(estado ){
    const adminSubmenu = document.getElementById('admin-submenu');
    const menuAdmin = document.getElementById('menu-admin');
    if (adminSubmenu) {
        
            adminSubmenu.display = 'none';
        }
    else {
        menuAdmin.innerHTML=`<li><a href="../Vista/AdminUsuario.php" id="nav-list">Usuarios</a></li>
                            <li><a href="../Vista/AdminEjercicios.php" id="nav-list">Ejercicios</a></li>
                            <li><a href="../Vista/AdminPlanes.php" id="nav-list">Planes</a></li>`
    }
}