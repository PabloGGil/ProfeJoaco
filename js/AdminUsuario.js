    import {PostData,getData} from './General.js';
    const UrlBase = "/vista/Ajax.php";
   
        
    // Variables globales
    let currentEditingId = null;
    const agregarBtn = document.getElementById('add-btn');    
    const formulario = document.getElementById('user-form');
    const tituloFormulario = document.getElementById('form-title');
    const enviarBtn = document.getElementById('submit-btn');
    const cancelarBtn = document.getElementById('cancel-btn');
    const listaContainer = document.getElementById('users-container');
    const seccionFormulario = document.getElementById('user-form-section');
    const seccionLista = document.getElementById('users-list-section');
    const containerError=document.getElementById('container-error');
    
        
    // Inicialización
    document.addEventListener('DOMContentLoaded', function() {           
        setupEventListeners();
        containerError.classList.add('hidden');
        Listar();
    });
        
    // Configurar event listeners
    function setupEventListeners() {
        enviarBtn.addEventListener('click', handleFormSubmit);
        agregarBtn.addEventListener('click',  (e) => {
           
            MostrarSeccion('form');
            resetForm();
        });

        cancelarBtn.addEventListener('click',  (e) => {
            MostrarSeccion('list');
        });
    }
        
    // Manejar envío del formulario
    function handleFormSubmit(e) {
    
        const formData = new FormData(formulario);
        const userData = {
            nombre: formData.get('nombre'),
            apellido: formData.get('apellido'),
            correo: formData.get('correo'),
            fechaNacimiento: formData.get('fechaNacimiento')
        };
        
        if (currentEditingId) {
            // Actualizar usuario existente
            updateUser(currentEditingId, userData);
        } else {
            // Crear nuevo usuario
            createUser(userData);
        }
    }
        
    // Crear nuevo usuario
    async function createUser(userData) {
        const newUser = {
            ...userData,
            // q:'CrearUsuario'
        };
        url=UrlBase+'Usuario/CrearUsuario';
        rta=await PostData(url,newUser);
       
        Listar();
        MostrarSeccion('list');
        
        alert('Usuario agregado correctamente');
    }
        
    // Actualizar usuario existente
    async function updateUser(id, userData) {
       
        userData['id']=id;
        userData['q']='EditarUsuario';
        
        rta=await PostData(userData);
        Listar();
        
        MostrarSeccion('list');
        
        alert('Usuario actualizado correctamente');
       
    }
    
    // Eliminar usuario
    async function deleteUser(id) {
        if (confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
            const delUsr = {
                id:id, // ID único basado en timestamp
            
                q:'EliminarUsuario'
            };
            rta=await PostData(delUsr);
            Listar();
            
            // Si estábamos editando este usuario, resetear el formulario
            if (currentEditingId === id) {
                resetForm();
            }
        }
    }
        
    // Editar usuario
    function editUser(id) {
        // const user = users.find(user => user.id === id);
        const row = document.querySelectorAll('#tr-'+id);
        const datos = Array.from(row[0].cells)
        const col=datos.map(td => td.innerText);
        currentEditingId = id;
        tituloFormulario.textContent = 'Editar Usuario';
        enviarBtn.textContent = 'Actualizar Usuario';
        cancelarBtn.style.display = 'block';

        MostrarSeccion('form');
       
            document.getElementById('nombre').value = col[0];
            document.getElementById('apellido').value = col[1];
            document.getElementById('correo').value = col[2];
            document.getElementById('fechaNacimiento').value = col[3];
    }
        
    // Resetear formulario
    function resetForm() {
        formulario.reset();
        currentEditingId = null;
        tituloFormulario.textContent = 'Agregar Nuevo Usuario';
        enviarBtn.textContent = 'Agregar Usuario';
        
    }
        
    // Renderizar lista de usuarios
    async function Listar() {
       
      
        // console.log(await getData('ListarUsuarios'));
        rta=await getData('ListarUsuarios');
        
        if(!rta.success){
            MostrarMensaje(rta.errorMessage,rta.errorCode);
            return;
        }
        if (rta.data.length === 0) {
            listaContainer.innerHTML = '<div class="no-data">No hay Usuarios registrados</div>';
            return;
        }

        
        let tableHTML = `
            <table class="users-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Correo</th>
                        <th>Fecha Nacimiento</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
        `;
      
        rta.data.forEach(user => {
            tableHTML += `
                <tr>
                    <td>${user.nombre}</td>
                    <td>${user.apellido}</td>
                    <td>${user.correo}</td>
                    
                    <td>${formatDate(user.fechaNacimiento)}</td>
                    <td>
                        <div class="action-buttons">
                            <button id=fila-${user.id} class="btn-edit" onclick="editUser(${user.id})">Editar</button>
                            <button id=fila-${user.id} class="btn-delete" onclick="deleteUser(${user.id})">Eliminar</button>
                        </div>
                    </td>
                </tr>
            `;
        });
        
        tableHTML += `
                </tbody>
            </table>
        `;
        
        listaContainer.innerHTML = tableHTML;
    }
        
    // Formatear fecha para mostrar
    function formatDate(fechaStr) {
        const options = { year: 'numeric', month: '2-digit', day: '2-digit' , timeZone:'UTC'};
        return new Date(fechaStr).toLocaleDateString('es-ES', options);

    }
        
    // Mostrar sección específica
    function MostrarSeccion(section) {
        if (section === 'form') {
            console.log("Mostrando formulario");
            seccionFormulario.classList.remove('hidden');
            seccionLista.classList.add('hidden');

        } else if (section === 'list') {
            console.log("Mostrando lista");
            seccionFormulario.classList.add('hidden');
            seccionLista.classList.remove('hidden');
            Listar();
        }
    }
    
    async function PostData(postData){
        try{
            opciones= {
                        method: 'POST', 
                        headers: {
                            'Content-Type': 'application/json', 
                        },
                        body: JSON.stringify(postData), 
                    }
            
            const respuesta= await fetch(UrlBase, opciones)

            if (!respuesta.ok) {
                MostrarMensaje(respuesta.errorCode,"alert-danger");
                throw new Error(`Error HTTP: ${respuesta.status}`);
                console.log(respuesta);
            }
            console.log(respuesta);
            return await respuesta.json();
        
            
        } catch (error) {
            console.error('Error en POST:', error);
            throw error;
        }
        
    }
    async function getData($servicio) {
        try{
            url=UrlBase + '?q='+$servicio;
            opciones= {
                        method: 'GET', 
                        headers: {
                            'Content-Type': 'application/json', 
                        },
            }
            const response= await fetch(url,opciones)
            if (!response.ok) {
                MostrarMensaje(response.errorCode,"alert-danger");
                throw new Error(`Error HTTP: ${response.status}`);
                console.log(response);
            }
            console.log(response);
            return await response.json();
        
            } catch (error) {
                console.log('Error en GET:', error);
    
            }
    
    }

    function MostrarMensaje(texto,tipo = 'success'){
        containerError.classList.remove('hidden');
        const div = document.getElementById('mensaje');
        div.innerHTML = `<div class="alert-danger" >${tipo}</div>`;
        div.innerHTML  += `<div class="alert" >${texto}</div>`;
    }
    // Hacer las funciones disponibles globalmente para los event listeners en línea
    window.editUser = editUser;
    window.deleteUser = deleteUser;