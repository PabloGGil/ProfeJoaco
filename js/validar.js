
import { PostData, getData } from './Api.js';
import { usuarioConfig } from './Config/UsuariosConfig.js';

    const btnRegistro=document.getElementById('btn-registro');    
    console.log(CryptoJS.AES.encrypt("canni", 'pp234').toString());

    btnRegistro.addEventListener('click', async function(e) {
        const Usuario = document.getElementById('correo').value;
        const Nombre = document.getElementById('nombre').value;
        const Apellido = document.getElementById('apellido').value;
        const FechaNacimiento = document.getElementById('fechanac').value== null ? '' : document.getElementById('fechanac').value;
        const Comentario = document.getElementById('comentarios').value== null ? '' : document.getElementById('comentarios').value;
        const passwd=document.getElementById('passwd').value;
        const rePasswd=document.getElementById('re-passwd').value;
        
        e.preventDefault();
        
        // Validación básica
        if (!Usuario || !Nombre || !Apellido || !passwd) {
            alert('Por favor, complete todos los campos obligatorios.');
            return;
        }
        if (passwd !== rePasswd) {
            alert('Las contraseñas no coinciden.');
            return;
        }
        // la verificacion que el usuario no existe se hace en el backend
        
        registrarUsuario();
       
       
           
        async function registrarUsuario() {
            
            const secretKey = 'pp234';
            const passwordEnc = CryptoJS.AES.encrypt(passwd, secretKey).toString();
           
            const datos = {
                nombre: Nombre,
                apellido: Apellido,
                correo: Usuario,
                fechanac: FechaNacimiento,
                comentario: Comentario,
                passwd:passwordEnc
            };
            
            const rta=await PostData(usuarioConfig.endpoints.crear,datos);
            if (!rta.success) {
                alert(rta.errorMessage);
                return;
            }
            // MostrarSeccion('list');
            // listar();
            alert(`¡Registro exitoso!\n\nNombre: ${datos.nombre} ${datos.apellido}\nFecha de Nacimiento: ${datos.fechanac}\nComentarios: ${datos.commentario || 'Ninguno'}`);
            window.location.href = '../index.php';
        }
    });


