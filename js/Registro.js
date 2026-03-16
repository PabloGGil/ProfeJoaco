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

    const formData = new FormData(formulario);
    const dataObj = {};
    fields.forEach(f => dataObj[f] = formData.get(f));

async function Registro(dataObj) {
      let rta = await PostData(endpoints.crear, dataObj);
      if (!rta.success) {
        MostrarMensaje(rta.errorMessage, rta.errorCode);
        return;
      }
      // MostrarSeccion('list');
      // listar();
      alert(`${entity} Registrado correctamente`);
}