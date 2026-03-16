
import { PostData, getData } from './Api.js';
document.addEventListener("DOMContentLoaded", function() {
    const toggles = document.querySelectorAll(".submenu-toggle");

    toggles.forEach(toggle => {
        toggle.addEventListener("click", function(e) {
            e.preventDefault(); // evita que se siga el link
            const parentLi = this.parentElement;
            parentLi.classList.toggle("open"); // alterna la clase "open"
        });
    });
});
    document.getElementById('userForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Obtener valores del formulario
            const Nombre = document.getElementById('nombre').value;
            const Apellido = document.getElementById('apellido').value;
            const FechaNacimiento = document.getElementById('fechanac').value;
            // const Comentarios = document.getElementById('commentarios').value;
            
            // Validación básica
            // if (!firstName || !lastName || !birthDate) {
            //     alert('Por favor, complete todos los campos obligatorios.');
            //     return;
            // }
            registrarUsuario();
            // Mostrar mensaje de éxito
            // alert(`¡Registro exitoso!\n\nNombre: ${nombre} ${apellido}\nFecha de Nacimiento: ${FechaNacimiento}\nComentarios: ${comments || 'Ninguno'}`);
            
            // Limpiar formulario
            // document.getElementById('userForm').reset();
        });

async function registrarUsuario() {
    const datos = {
        nombre: document.getElementById('nombre').value,
        apellido: document.getElementById('apellido').value,
        correo: document.getElementById('correo').value,
        fechanac: document.getElementById('fechanac').value,
        comentario: document.getElementById('comentarios').value,
    };
    
    const rta=await PostData("Usuario/CrearUsuario",datos);
    if (!rta.success) {
        alert(rta.errorMessage);
        return;
      }
      // MostrarSeccion('list');
      // listar();
      alert(`¡Registro exitoso!\n\nNombre: ${datos.nombre} ${datos.apellido}\nFecha de Nacimiento: ${datos.fechanac}\nComentarios: ${datos.commentario || 'Ninguno'}`);
}