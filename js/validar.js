const UrlBase = "/vista/Ajax.php";

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
            const FechaNacimiento = document.getElementById('fechaNacimiento').value;
            const Comentarios = document.getElementById('comments').value;
            
            // Validación básica
            // if (!firstName || !lastName || !birthDate) {
            //     alert('Por favor, complete todos los campos obligatorios.');
            //     return;
            // }
            registrarUsuario();
            // Mostrar mensaje de éxito
            alert(`¡Registro exitoso!\n\nNombre: ${nombre} ${apellido}\nFecha de Nacimiento: ${FechaNacimiento}\nComentarios: ${comments || 'Ninguno'}`);
            
            // Limpiar formulario
            // document.getElementById('userForm').reset();
        });

function registrarUsuario() {
    const datos = {
        nombre: document.getElementById('nombre').value,
        apellido: document.getElementById('apellido').value,
        correo: document.getElementById('correo').value,
        fechaNacimiento: document.getElementById('correo').value,
        comentarios: document.getElementById('comentarios').value,
    };
    
    fetch(`${UrlBase}?accion=CrearUsuario`, { 
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(datos)
    })
    .then((response) => response.json())
    .then(data => {
        console.log('Usuario registrado:', data);
    })
    .catch((error) => {
        console.error('Error:', error);
    });
}