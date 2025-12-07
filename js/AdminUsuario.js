    const UrlBase = "/vista/Ajax.php";
    let users = JSON.parse(localStorage.getItem('users')) || [
            {
                id: 1,
                nombre: 'Ana',
                apellido: 'García',
                correo: 'ana.garcia@example.com',
                fechaNacimiento: '1990-05-15'
            },
            {
                id: 2,
                nombre: 'Carlos',
                apellido: 'López',
                correo: 'carlos.lopez@example.com',
                fechaNacimiento: '1985-11-22'
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
    const formularioSeccion = document.getElementById('user-form-section');
    const listaSeccion = document.getElementById('users-list-section');
    
        
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
    function createUser(userData) {
        const newUser = {
            id: Date.now(), // ID único basado en timestamp
            ...userData
        };
        console.log(newUser);
        users.push(newUser);
        console.log(users);
        saveUsers();
        Listar();
        MostrarSeccion('list');
        
        alert('Usuario agregado correctamente');
    }
        
    // Actualizar usuario existente
    function updateUser(id, userData) {
        const userIndex = users.findIndex(user => user.id === id);
        
        if (userIndex !== -1) {
            users[userIndex] = { id, ...userData };
            saveUsers();
            Listar();
            resetForm();
            MostrarSeccion('list');
            
            alert('Usuario actualizado correctamente');
        }
    }
    
    // Eliminar usuario
    function deleteUser(id) {
        if (confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
            users = users.filter(user => user.id !== id);
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
        const user = users.find(user => user.id === id);
        
        if (user) {
            document.getElementById('nombre').value = user.nombre;
            document.getElementById('apellido').value = user.apellido;
            document.getElementById('correo').value = user.correo;
            document.getElementById('fechaNacimiento').value = user.fechaNacimiento;
            
            currentEditingId = id;
            tituloFormulario.textContent = 'Editar Usuario';
            enviarBtn.textContent = 'Actualizar Usuario';
            cancelarBtn.style.display = 'block';
            
            MostrarSeccion('form');
        }
    }
        
    // Resetear formulario
    function resetForm() {
        formulario.reset();
        currentEditingId = null;
        tituloFormulario.textContent = 'Agregar Nuevo Usuario';
        enviarBtn.textContent = 'Agregar Usuario';
        cancelarBtn.style.display = 'none';
    }
        
    // Renderizar lista de usuarios
    function Listar() {
        if (users.length === 0) {
            listaContainer.innerHTML = '<div class="no-users">No hay usuarios registrados</div>';
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
        // <td>${formatDate(user.fechaNacimiento)}
        users.forEach(user => {
            tableHTML += `
                <tr>
                    <td>${user.nombre}</td>
                    <td>${user.apellido}</td>
                    <td>${user.correo}</td>
                    
                    <td>${formatDate(user.fechaNacimiento)}</td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-edit" onclick="editUser(${user.id})">Editar</button>
                            <button class="btn-delete" onclick="deleteUser(${user.id})">Eliminar</button>
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
        localStorage.setItem('users', JSON.stringify(users));
    }
        
    // Mostrar sección específica
    function MostrarSeccion(section) {
        if (section === 'form') {
            console.log("Mostrando formulario");
            formularioSeccion.classList.remove('hidden');
            listaSeccion.classList.add('hidden');

        } else if (section === 'list') {
            console.log("Mostrando lista");
            formularioSeccion.classList.add('hidden');
            listaSeccion.classList.remove('hidden');
            Listar();
        }
    }
        
    // Hacer las funciones disponibles globalmente para los event listeners en línea
    window.editUser = editUser;
    window.deleteUser = deleteUser;