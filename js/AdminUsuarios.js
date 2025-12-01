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
        
        // Elementos del DOM
        const userForm = document.getElementById('user-form');
        const formTitle = document.getElementById('form-title');
        const submitBtn = document.getElementById('submit-btn');
        const cancelBtn = document.getElementById('cancel-btn');
        const usersContainer = document.getElementById('users-container');
        const userFormSection = document.getElementById('user-form-section');
        const usersListSection = document.getElementById('users-list-section');
        const navForm = document.getElementById('nav-form');
        const navList = document.getElementById('nav-list');
        
        // Inicialización
        document.addEventListener('DOMContentLoaded', function() {
            renderUsers();
            setupEventListeners();
        });
        
        // Configurar event listeners
        function setupEventListeners() {
            userForm.addEventListener('submit', handleFormSubmit);
            cancelBtn.addEventListener('click', resetForm);
            navForm.addEventListener('click', () => showSection('form'));
            navList.addEventListener('click', () => showSection('list'));
        }
        
        // Manejar envío del formulario
        function handleFormSubmit(e) {
            e.preventDefault();
            
            const formData = new FormData(userForm);
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
            
            users.push(newUser);
            saveUsers();
            renderUsers();
            resetForm();
            showSection('list');
            
            alert('Usuario agregado correctamente');
        }
        
        // Actualizar usuario existente
        function updateUser(id, userData) {
            const userIndex = users.findIndex(user => user.id === id);
            
            if (userIndex !== -1) {
                users[userIndex] = { id, ...userData };
                saveUsers();
                renderUsers();
                resetForm();
                showSection('list');
                
                alert('Usuario actualizado correctamente');
            }
        }
        
        // Eliminar usuario
        function deleteUser(id) {
            if (confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
                users = users.filter(user => user.id !== id);
                saveUsers();
                renderUsers();
                
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
                formTitle.textContent = 'Editar Usuario';
                submitBtn.textContent = 'Actualizar Usuario';
                cancelBtn.style.display = 'block';
                
                showSection('form');
            }
        }
        
        // Resetear formulario
        function resetForm() {
            userForm.reset();
            currentEditingId = null;
            formTitle.textContent = 'Agregar Nuevo Usuario';
            submitBtn.textContent = 'Agregar Usuario';
            cancelBtn.style.display = 'none';
        }
        
        // Renderizar lista de usuarios
        function renderUsers() {
            if (users.length === 0) {
                usersContainer.innerHTML = '<div class="no-users">No hay usuarios registrados</div>';
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
            
            usersContainer.innerHTML = tableHTML;
        }
        
        // Formatear fecha para mostrar
        function formatDate(dateString) {
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            return new Date(dateString).toLocaleDateString('es-ES', options);
        }
        
        // Guardar usuarios en localStorage
        function saveUsers() {
            localStorage.setItem('users', JSON.stringify(users));
        }
        
        // Mostrar sección específica
        function showSection(section) {
            if (section === 'form') {
                userFormSection.classList.remove('hidden');
                usersListSection.classList.add('hidden');
                navForm.classList.add('active');
                navList.classList.remove('active');
            } else if (section === 'list') {
                userFormSection.classList.add('hidden');
                usersListSection.classList.remove('hidden');
                navForm.classList.remove('active');
                navList.classList.add('active');
                renderUsers();
            }
        }
        
        // Hacer las funciones disponibles globalmente para los event listeners en línea
        window.editUser = editUser;
        window.deleteUser = deleteUser;