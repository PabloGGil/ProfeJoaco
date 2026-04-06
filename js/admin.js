import { PostData, getData } from './Api.js';
// import {editPlanes,cargarEjercicios,guardarPlan} from './ABMPlanes.js'

export function setupCrud(config) {
  let currentEditingId = null;

  const {
    entity,
    fields,
    endpoints,
    renderRow,
    columnas
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
  const filtro=document.getElementById(`filtro${entity}`);
  
  const indicadorCarga = document.getElementById('indicador-carga');
 
  if (document.readyState === 'loading') {
      console.log("DOM aún cargando, esperando evento");
      document.addEventListener('DOMContentLoaded', iniciar);
  } else {
      console.log("DOM ya cargado, ejecutando inmediatamente");
      iniciar();
  }

  function iniciar() {
    console.log("Iniciando aplicación");
    
    setupEventListeners();
    containerError.classList.add('hidden');
    if(config.entity=="Planes"){
      // window.cargarEjercicios();
      ListarPlanes();
    }else{
      listar();
    }
  }
  

  function setupEventListeners() {
    enviarBtn.addEventListener('click', handleFormSubmit);
    agregarBtn.addEventListener('click', () => {
      MostrarSeccion('form');
      if (typeof window.cargarEjercicios === 'function') {
          window.cargarEjercicios(dataObj);
        } else {
          console.log('cargarEjercicios no está definida');
        }
      resetForm();
    });
    cancelarBtn.addEventListener('click', () => {
      MostrarSeccion('list');
      if(config.entity=="Planes"){
        // document.getElementById('btnGuardar').addEventListener('click', guardarPlan);
        ListarPlanes();
      }else{
        listar();
      }
    });
    
    filtro.addEventListener('keyup',filtrarTabla);
    
  }

  function handleFormSubmit(e) {
    const formData = new FormData(formulario);
    const dataObj = {};
    fields.forEach(f => {dataObj[f] = formData.get(f);
      console.log("form data "+f +"valor "+ formData.get(f));
    });
    if (config.entity === "Planes") {
      if (currentEditingId) {
        // CORRECCIÓN 5: Verificar si la función global existe
        if (typeof window.editPlanes === 'function') {
          window.editPlanes(dataObj);
        } else {
          console.error('editPlanes no está definida');
        }
      } else {
        if (typeof window.guardarPlan === 'function') {
          window.guardarPlan();
        } else {
          console.error('guardarPlan no está definida');
        }
      }}else{
      if (currentEditingId) {
        update(currentEditingId, dataObj);
      } else {
        console.log(dataObj);
        create(dataObj);
      }
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

  async function update(id, dataObj) {
    dataObj.id = id;
    let rta = await PostData(endpoints.editar, dataObj);
    MostrarSeccion('list');
    listar();
    alert(`${entity} actualizado correctamente`);
  }

  async function remove(id) {
    // const row = document.querySelector(`#tr-${id}`);
    // const datos = Array.from(row.cells).map(td => td.innerText);
    if (confirm(`¿Estás seguro de eliminar este ${entity} ?`)) {
      let rta = await PostData(endpoints.eliminar,  id );
      if (!rta.success) {
        MostrarMensaje(rta.errorMessage, rta.errorCode);
        return;
      }
      if(config.entity=="Planes"){
        ListarPlanes();
      }else{
        listar();
      }
      if (currentEditingId === id) resetForm();
    }
  }

  function resetForm() {
    formulario.reset();
    currentEditingId = null;
    tituloFormulario.textContent = `Agregar Nuevo ${entity}`;
    enviarBtn.textContent = `Agregar ${entity}`;
  }

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

  window.MostrarMensaje=MostrarMensaje;
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
          // Listar();
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
          mensajeNoResultados.textContent = 'No se encontraron usuarios que coincidan con el filtro';
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
  window.MostrarSeccion = MostrarSeccion;
  window.ListarPlanes = ListarPlanes;
  // window.editPlanes = editPlanes;
  window.deletePlanes = remove;
  async function ListarPlanes() {
      // Ocultar loading
      indicadorCarga.style.display = 'none';
      const rta = await getData(endpoints.listar);
      if (!rta.success) {
        MostrarMensaje(rta.errorMessage, rta.errorCode);
        return;
      }
      const planes=rta.data;
      console.log(planes);
      if (planes.length === 0) {
          listaContainer.innerHTML = '<div class="no-resultados">No se encontraron planes</div>';
          return;
      }
      // Crear tabla
      let html = `
          <table class="tabla" id="tabla-${entity}">                
          <thead>
            <tr>
              ${columnas.map(f => `<th>${f}</th>`).join('')}
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
      `;
      // Recorrer planes para generar filas
      planes.forEach(plan => {
        const totalEjercicios = plan.ejercicios.length;
        // Verificar si tiene ejercicios
        if (totalEjercicios > 0) {
            // Primera fila (con rowspan)
          html += `
            <tr  data-${entity}="${plan.pnombre}" id='tr-${plan.id}'>
                <td rowspan="${totalEjercicios}" >
                    ${plan.pnombre}
                    <span class="badge-count">(${totalEjercicios} Ejercicios)</span>
                </td>
                <td rowspan="${totalEjercicios}">
                    ${plan.descripcion}
                </td>
                <td>
                    <span class="grupo-item">${plan.ejercicios[0].nombre}</span>
                </td>
                <td>
                    <span class="grupo-item">${plan.ejercicios[0].grupoMuscular}</span>
                </td>
                <td>
                    <span class="grupo-item">${plan.ejercicios[0].repeticiones}</span>
                </td>
                <td>
                    <span class="grupo-item">${plan.ejercicios[0].series}</span>
                </td>
                <td>
                    <span class="grupo-item">${plan.ejercicios[0].peso}</span>
                </td>
                <td rowspan="${totalEjercicios}">
                  <button class="btn-edit" onclick="editPlanes('${plan.pnombre}')">Editar</button>
                  <button class="btn-delete" onclick="deletePlanes('${plan.pnombre}')">Eliminar</button>
                </td>
            </tr>
          `;
          
          // ejercicios restantes
          for (let i = 1; i < totalEjercicios; i++) {
            html += `
                <tr  data-${entity}="${plan.pnombre}" id='tr-${plan.id}'>
                    <td>
                        <span class="grupo-item">${plan.ejercicios[i].nombre}</span>
                    </td>
                    <td>
                        <span class="grupo-item">${plan.ejercicios[i].grupoMuscular}</span>
                    </td>
                    <td>
                        <span class="grupo-item">${plan.ejercicios[i].repeticiones}</span>
                    </td>
                    <td>
                        <span class="grupo-item">${plan.ejercicios[i].series}</span>
                    </td>
                    <td>
                        <span class="grupo-item">${plan.ejercicios[i].peso}</span>
                    </td>
                    
                </tr>
            `;
          }
        } else {
            // plan sin ejercicios
            html += `
                <tr  data-${entity}="${plan.pnombre}">
                    <td class="usuario-nombre">
                        ${plan.pnombre}
                        <span class="badge-count">0 Ejercicios</span>
                    </td>
                    <td>${plan.descripcion}</td>
                    <td>
                        <em style="color: #999;">Sin grupos asignados</em>
                    </td>
                </tr>
            `;
        }
      });
      
      html += `
              </tbody>
          </table>
      `;
      
      listaContainer.innerHTML = html;
  }

  
  // Exponer funciones para onclick
  window[`edit${entity}`] = edit;
  window[`delete${entity}`] = remove;

}
