import { PostData, getData } from './Api.js';

export function initAdmin(config) {

      console.log(`Inicializando modulo ${config.entity}...`);
      setupCrud(config);

  }

export function setupCrud(config,customHandlers={}) {
  let currentEditingId = null;

  const {
    entity,
    fields,
    endpoints,
    renderRow,
    columnas,
  } = config;

  const agregarBtn = document.getElementById('add-btn');
  const formulario = document.getElementById('formulario');
  const tituloFormulario = document.getElementById('form-title');
  const enviarBtn = document.getElementById('submit-btn');
  const cancelarBtn = document.getElementById('cancel-btn');
  const listaContainer = document.getElementById('container-data');
  const containerError = document.getElementById('container-error');
  const seccionFormulario = document.getElementById('form-section');
  const seccionLista = document.getElementById('list-section');
  const filtro=document.getElementById('filtro');
  const indicadorCarga = document.getElementById('indicador-carga');
 
  // Creacion de los manejadores en base al archivo de configuracion
  const handlers = {
    onCreate: customHandlers.onCreate || create,
    onEdit: customHandlers.onEdit || edit,
    onUpdate: customHandlers.onUpdate || update,
    onDelete: customHandlers.onDelete || remove,
    onList: customHandlers.onList || listar,
    onInit: customHandlers.onInit || null , // Para inicialización especial
    onShowEj: customHandlers.onShowEj || null
  };
console.log(handlers);
  // Esperar a que cargue la pagina
  if (document.readyState === 'loading') {
      console.log("DOM aún cargando, esperando evento");
      document.addEventListener('DOMContentLoaded', iniciar);
  } else {
      console.log("DOM ya cargado, ejecutando inmediatamente");
      iniciar();
  }

  
  function iniciar() {
    console.log("Iniciando aplicación");
    if (handlers.onInit) {
      handlers.onInit();
    }
    setupEventListeners();
    containerError.classList.add('hidden');
    handlers.onList();
   
  }
  
  // Listeners --> boton enviar, boton agregar, boton cancelar y filtro
  function setupEventListeners() {
    enviarBtn.addEventListener('click', handleFormSubmit);
    agregarBtn.addEventListener('click', () => {
      MostrarSeccion('form');
      if (handlers.onShowEj) {
          handlers.onShowEj();
        } else {
          console.log('cargarEjercicios no está definida');
        }
      resetForm();
    });
    cancelarBtn.addEventListener('click', () => {
      MostrarSeccion('list');
      handlers.onList;
    });
    
    filtro.addEventListener('keyup',filtrarTabla);
    
  }

  function handleFormSubmit(e) {
    const formData = new FormData(formulario);
    const dataObj = {};
    fields.forEach(f => {dataObj[f] = formData.get(f);
      console.log("form data "+f +"valor "+ formData.get(f));
    });
    
    if (currentEditingId) {
      if (handlers.onUpdate) {
        handlers.onUpdate(currentEditingId,dataObj);
      }
    } else {
        handlers.onCreate(dataObj)
    }   
  }

  async function create(dataObj) {
    let rta = await PostData(endpoints.crear, dataObj);
    if (!rta.success) {
      MostrarMensaje(rta.errorMessage, rta.errorCode);
      return;
    }
    MostrarSeccion('list');
    listar();
    alert(`${entity} agregado correctamente`);
  }

/* -------------------------------------------
  Funcion EDIT -------------------------------
  -------------------------------------------*/
  function edit(id) {
    const row = document.querySelector(`#tr-${id}`);
    const datos = Array.from(row.cells).map(td => td.innerText);
    currentEditingId = id;
    if(config.entity=="Planes"){
      const row = document.querySelector(`#tr-${id}`);
      editPlanes(row)
    }else{
        
      tituloFormulario.textContent = `Editar ${entity}`;
      enviarBtn.textContent = `Actualizar ${entity}`;
      cancelarBtn.style.display = 'block';
      MostrarSeccion('form');
      fields.forEach((f, i) => {
        //Si hay fechas hay que convertirlas a formato ISO
        if(f=="fecha_nacimiento"){
          const [dia, mes, año] = datos[i].split("/"); 
          const fechaISO = `${año}-${String(mes).padStart(2, '0')}-${String(dia).padStart(2, '0')}`;
          document.getElementById(f).value = fechaISO;
        }else{

          document.getElementById(f).value = datos[i];
        }
      });
    }
  }

  /* -------------------------------------------
  Funcion UPDATE -------------------------------
  -------------------------------------------*/

  async function update(id, dataObj) {
    dataObj.id = id;
    let rta = await PostData(endpoints.editar, dataObj);
    MostrarSeccion('list');
    listar();
    alert(`${entity} actualizado correctamente`);
  }

  /* -------------------------------------------
  Funcion REMOVE -------------------------------
  -------------------------------------------*/
  async function remove(id) { 
    if (confirm(`¿Estás seguro de eliminar este ${entity} ?`)) {
      let rta = await PostData(endpoints.eliminar,  id );
      if (!rta.success) {
        MostrarMensaje(rta.errorMessage, rta.errorCode);
        return;
      }
      handlers.onList();
      if (currentEditingId === id) resetForm();
    }
  }

/* -------------------------------------------
  Funcion REMOVE -------------------------------
  -------------------------------------------*/
  async function listar() {
    const rta = await getData(endpoints.listar);
    if (!rta.success) {
      MostrarMensaje(rta.errorMessage, rta.errorCode);
      return;
    }
    if (rta.data.length === 0) {
      listaContainer.innerHTML = `<div class="no-data">No hay ${entity}s registrados</div>`;
      return;
    }

    let tableHTML = `
      <table class="tabla" id="tabla-${entity}">
        <thead>
          <tr>
            ${columnas.map(f => `<th>${f}</th>`).join('')}
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
    `;

    rta.data.forEach(obj => {
      tableHTML += renderRow(obj, { edit, remove });
    });

    tableHTML += `</tbody></table>`;
    listaContainer.innerHTML = tableHTML;
  }

  /* -------------------------------------------
  Funciones AUXILIARES--------------------------
  -------------------------------------------*/
    function resetForm() {
    formulario.reset();
    currentEditingId = null;
    tituloFormulario.textContent = `Agregar Nuevo ${entity}`;
    enviarBtn.textContent = `Agregar ${entity}`;
  }


  function MostrarMensaje(texto,tipo = 'success'){
    containerError.classList.remove('hidden');
    const div = document.getElementById('mensaje');
    div.innerHTML = `<div class="alert-danger" >${tipo}</div>`;
    div.innerHTML  += `<div class="alert" >${texto}</div>`;
  }

  function MostrarSeccion(section) {
      if (section === 'form') {
          console.log("Mostrando formulario");
          seccionFormulario.classList.remove('hidden');
          seccionLista.classList.add('hidden');

      } else if (section === 'list') {
          console.log("Mostrando lista");
          seccionFormulario.classList.add('hidden');
          seccionLista.classList.remove('hidden');
         
      }
  }
  
  function filtrarTabla() {
    const filtro = document.getElementById(`filtro${entity}`).value.toLowerCase();
    const tabla = document.getElementById(`tabla-${entity}`);
    console.log(`ehhhh  tabla-${entity}`);
    if (!tabla) return;
    
    const filas = tabla.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    const objVisibles = new Set();
    
      // Primero, determinar qué planes son visibles
    Array.from(filas).forEach(fila => {
        const obj = fila.getAttribute(`data-${entity}`);
        if (obj && obj.toLowerCase().includes(filtro)) {
            objVisibles.add(obj);
        }
    });
    
    // Luego, mostrar/ocultar filas según el usuario
    Array.from(filas).forEach(fila => {
        const obj = fila.getAttribute(`data-${entity}`);
        if (objVisibles.has(obj) || filtro === '') {
            fila.style.display = '';
        } else {
            fila.style.display = 'none';
        }
    });
        
    // Mostrar mensaje si no hay resultados
    const tbody = tabla.getElementsByTagName('tbody')[0];
    const filasVisibles = Array.from(filas).filter(fila => fila.style.display !== 'none').length;
    
    let mensajeNoResultados = document.getElementById('mensaje-no-resultados');
    
    if (filasVisibles === 0) {
      if (!mensajeNoResultados) {
          mensajeNoResultados = document.createElement('div');
          mensajeNoResultados.id = 'mensaje-no-resultados';
          mensajeNoResultados.className = 'no-resultados';
          mensajeNoResultados.textContent = 'No se encontraron OBJETOS que coincidan con el filtro';
          tabla.parentNode.insertBefore(mensajeNoResultados, tabla.nextSibling);
      }
    } else {
      if (mensajeNoResultados) {
          mensajeNoResultados.remove();
      }
    }
  }
        
  // Función para limpiar filtro
  function limpiarFiltro() {
      document.getElementById('filtroUsuario').value = '';
      filtrarTabla();
  }
  
  // Exponer funciones para onclick
  window[`edit${entity}`] = edit;
  window[`delete${entity}`] = remove;

}
