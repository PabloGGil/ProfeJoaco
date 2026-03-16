import { PostData, getData } from './Api.js';

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
 
  document.addEventListener('DOMContentLoaded', () => {
    setupEventListeners();
    containerError.classList.add('hidden');
    listar();
  });

  function setupEventListeners() {
    enviarBtn.addEventListener('click', handleFormSubmit);
    agregarBtn.addEventListener('click', () => {
      MostrarSeccion('form');
      resetForm();
    });
    cancelarBtn.addEventListener('click', () => {
      MostrarSeccion('list');
      listar();
    });

  }

  function handleFormSubmit(e) {
    const formData = new FormData(formulario);
    const dataObj = {};
    fields.forEach(f => dataObj[f] = formData.get(f));

    if (currentEditingId) {
      update(currentEditingId, dataObj);
    } else {
      create(dataObj);
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
    tituloFormulario.textContent = `Editar ${entity}`;
    enviarBtn.textContent = `Actualizar ${entity}`;
    cancelarBtn.style.display = 'block';

    MostrarSeccion('form');
    fields.forEach((f, i) => {
      //Si hay fechas hay que convertirlas a formato ISO
      if(f=="fechanac"){
        const [dia, mes, año] = datos[i].split("/"); 
        const fechaISO = `${año}-${mes}-${dia}`;
        document.getElementById(f).value = fechaISO;
      }else{

        document.getElementById(f).value = datos[i];
      }
    });
  }

  async function update(id, dataObj) {
    dataObj.id = id;
    let rta = await PostData(endpoints.editar, dataObj);
    MostrarSeccion('list');
    listar();
    alert(`${entity} actualizado correctamente`);
  }

  async function remove(id) {
    if (confirm(`¿Estás seguro de eliminar este ${entity}?`)) {
      let rta = await PostData(endpoints.eliminar, { id });
      if (!rta.success) {
        MostrarMensaje(rta.errorMessage, rta.errorCode);
        return;
      }
      listar();
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
      <table class="tabla">
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
  
  // Exponer funciones para onclick
  window[`edit${entity}`] = edit;
  window[`delete${entity}`] = remove;
}
