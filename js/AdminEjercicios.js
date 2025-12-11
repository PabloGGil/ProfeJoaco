    const UrlBase = "/vista/Ajax.php";
    let objetos = JSON.parse(localStorage.getItem('ejercicios')) || [
            {
                id: 1,
                musculo: 'Ana',
                ejercicio: 'García',
                explicacion: 'ana.garcia@example.com',
               
            },
            {
                id: 2,
                musculo: 'Carlos',
                ejercicio: 'López',
                explicacion: 'carlos.lopez@example.com',
                
            }
        ];
        
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
    
        
    // Inicialización
    document.addEventListener('DOMContentLoaded', function() {           
        setupEventListeners();
        Listar();
    });
        
    // Configurar event listeners
    function setupEventListeners() {
        enviarBtn.addEventListener('click', handleFormSubmit);
        agregarBtn.addEventListener('click',  (e) => {
           
            MostrarSeccion('form');
        });

        cancelarBtn.addEventListener('click',  (e) => {
            MostrarSeccion('list');
        });
    }
        
    // Manejar envío del formulario
    function handleFormSubmit(e) {
    
        const formData = new FormData(formulario);
        const dataObj = {
            musculo: formData.get('musculo'),
            ejercicio: formData.get('ejercicio'),
            explicacion: formData.get('explicacion'),
           
        };
        
        if (currentEditingId) {
            // Actualizar usuario existente
            updateUser(currentEditingId, dataObj);
        } else {
            // Crear nuevo usuario
            createUser(dataObj);
        }
    }
        
    // Crear nuevo usuario
    function createUser(dataObj) {
        const newUser = {
            id: Date.now(), // ID único basado en timestamp
            ...dataObj
        };
        console.log(newUser);
        objetos.push(newUser);
        console.log(objetos);
        saveUsers();
        Listar();
        MostrarSeccion('list');
        
        alert('Ejercicio agregado correctamente');
    }
        
    // Actualizar usuario existente
    function updateUser(id, dataObj) {
        const userIndex = objetos.findIndex(obj => obj.id === id);
        
        if (userIndex !== -1) {
            objetos[userIndex] = { id, ...dataObj };
            saveUsers();
            Listar();
            resetForm();
            MostrarSeccion('list');
            
            alert('Ejercicio actualizado correctamente');
        }
    }
    
    // Eliminar usuario
    function deleteUser(id) {
        if (confirm('¿Estás seguro de que deseas eliminar este ejercicio?')) {
            objetos = objetos.filter(obj => obj.id !== id);
            saveUsers();
            Listar();
            
            // Si estábamos editando este usuario, resetear el formulario
            if (currentEditingId === id) {
                resetForm();
            }
        }
    }
        
    // Editar usuario
    function editUser(id) {
        const ej = objetos.find(ej => ej.id === id);
        
        if (ej) {
            document.getElementById('musculo').value = ej.musculo;
            document.getElementById('ejercicio').value = ej.ejercicio;
            document.getElementById('explicacion').value = ej.explicacion;
            
            
            currentEditingId = id;
            tituloFormulario.textContent = 'Editar Ejercicio';
            enviarBtn.textContent = 'Actualizar Ejercicio';
            cancelarBtn.style.display = 'block';
            
            MostrarSeccion('form');
        }
    }
        
    // Resetear formulario
    function resetForm() {
        formulario.reset();
        currentEditingId = null;
        tituloFormulario.textContent = 'Agregar Nuevo Ejercicio';
        enviarBtn.textContent = 'Agregar Ejercicio';
        cancelarBtn.style.display = 'none';
    }
        
    // Renderizar lista de usuarios
    function Listar() {
        console.log(getData());
        console.log(objetos);
        objetos=getData();
        if (objetos.length === 0) {
            listaContainer.innerHTML = '<div class="no-users">No hay Ejercicio registrados</div>';
            return;
        }
        
        let tableHTML = `
            <table class="users-table">
                <thead>
                    <tr>
                        <th>Musculo</th>
                        <th>Ejercicio</th>
                        <th>Explicacion</th>                       
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
        `;
  
        objetos.forEach(obj => {
            tableHTML += `
                <tr>
                    <td>${obj.musculo}</td>
                    <td>${obj.ejercicio}</td>
                    <td>${obj.explicacion}</td>

                    <td>
                        <div class="action-buttons">
                            <button class="btn-edit" onclick="editUser(${obj.id})">Editar</button>
                            <button class="btn-delete" onclick="deleteUser(${obj.id})">Eliminar</button>
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
    
    // Guardar usuarios en localStorage
    function saveUsers() {
        localStorage.setItem('ejercicios', JSON.stringify(objetos));
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
        
     function PostData(postData){
        try{
            opciones= {
                        method: 'POST', // Specify the HTTP method
                        headers: {
                            'Content-Type': 'application/json', // Indicate the content type
                        },
                        body: JSON.stringify(postData), // Convert data to JSON string
                    }
            
            const respuesta= fetch(UrlBase, opciones)

            .then(response=> {
                return  respuesta.json();
                throw new Error(`Error HTTP: ${respresponseuesta.status}`);
            });
        
            
        } catch (error) {
            console.error('Error en POST:', error);
            throw error;
        }
        
    }
    function getData($servicio) {
   
        url=UrlBase + '?q='+$servicio;
         opciones= {
                        method: 'GET', // Specify the HTTP method
                        headers: {
                            'Content-Type': 'application/json', // Indicate the content type
                        },
                    }
        fetch(url,opciones)
        .then(response => response.json()) 
        .then(json => console.log(json));
    
}
    // Hacer las funciones disponibles globalmente para los event listeners en línea
    window.editUser = editUser;
    window.deleteUser = deleteUser;