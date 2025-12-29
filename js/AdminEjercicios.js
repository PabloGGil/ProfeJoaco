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
        const dataObj = {
            musculo: formData.get('musculo'),
            ejercicio: formData.get('ejercicio'),
            explicacion: formData.get('explicacion'),           
        };
        
        if (currentEditingId) {
            // Actualizar usuario existente
            updateEjercicio(currentEditingId, dataObj);
        } else {
            // Crear nuevo usuario
            createEjercicio(dataObj);
        }
    }
    async function updateEjercicio(id, data) {
        
        data['id']=id;
        data['q']='EditarEjercicio';
        if (id !== -1) {
            
            rta=await PostData(data);
            Listar();
          
            MostrarSeccion('list');
            
            alert('Usuario actualizado correctamente');
        }
    }
    // Crear nuevo usuario
    async function createEjercicio(dataObj) {
        const newEjercicio = {
            // id: Date.now(), // ID único basado en timestamp
            ...dataObj,
            q:'CrearEjercicio'
        };
     
        // currentEditingId=null;
        // console.log(newEjercicio);
        rta=await PostData(newEjercicio);
        console.log(objetos);
       
        Listar();
        MostrarSeccion('list');
        
        alert('Ejercicio agregado correctamente');
    }
        
    // Actualizar usuario existente
    function editEjercicio(id) {
                
        const row = document.querySelectorAll('#tr-'+id);
        const datos = Array.from(row[0].cells)
        const col=datos.map(td => td.innerText);
        
        currentEditingId = id;
        tituloFormulario.textContent = 'Editar Ejercicio';
        enviarBtn.textContent = 'Actualizar Ejercicio';
        cancelarBtn.style.display = 'block';
            
        MostrarSeccion('form');
        document.getElementById('musculo').value = col[0];//datos[0];
        document.getElementById('ejercicio').value = col[1];
        document.getElementById('explicacion').value = col[2];
 
    }
    
    // Eliminar usuario
    async function deleteEjercicio(id) {
        if (confirm('¿Estás seguro de que deseas eliminar este ejercicio?')) {
            
            const delEj = {
                id:id, // ID único basado en timestamp
            
                q:'EliminarEjercicio'
            };
            rta=await PostData(delEj);
            Listar();
            
            // Si estábamos editando este usuario, resetear el formulario
            if (currentEditingId === id) {
                resetForm();
            }
        }
    }
        
    // Resetear formulario
    function resetForm() {
        formulario.reset();
        currentEditingId = null;
        tituloFormulario.textContent = 'Agregar Nuevo Ejercicio';
        enviarBtn.textContent = 'Agregar Ejercicio';
        // cancelarBtn.style.display = 'none';
    }
        
    // Renderizar lista de Ejercicios
    async function Listar() {
        
        objetos=await getData('ListarEjercicios');
        console.log(objetos);
        if(!objetos.success){
            MostrarMensaje(objetos.errorMessage,objetos.errorCode);
            return;
        }
        if (objetos.data.length === 0) {
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
  
        objetos.data.forEach(obj => {
            tableHTML += `
                <tr id=tr-${obj.id}>
                    <td>${obj.musculo}</td>
                    <td>${obj.ejercicio}</td>
                    <td>${obj.explicacion}</td>

                    <td>
                        <div class="action-buttons">
                            <button id=fila-${obj.id} class="btn-edit" onclick="editEjercicio(${obj.id})">Editar</button>
                            <button id=fila-${obj.id} class="btn-delete" onclick="deleteEjercicio(${obj.id})">Eliminar</button>
                        </div>
                    </td>
                </tr>
            `;
        });
                            //      <button id=edt'${obj.id}' class="btn-edit" onclick="editEjercicio(${obj.id})">Editar</button>
                            // <button id=del'${obj.id}' class="btn-delete" onclick="deleteEjercicio(${obj.id})">Eliminar</button>
  
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
    // function saveUsers() {
    //     localStorage.setItem('ejercicios', JSON.stringify(objetos));
    // }
        
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
    window.editEjercicio = editEjercicio;
    window.deleteEjercicio = deleteEjercicio;