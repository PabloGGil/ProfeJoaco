

import{setupCrud } from './admin.js';


setupCrud({
  entity: "Planes",
  fields: ["pnombre", "descripcion", "nombre", "grupo_muscular","repeticiones", "series", "peso"],
  columnas: ["Plan", "Descripcion", "ejercicio", "Musculo","repeticiones", "series", "peso"],
  endpoints: {
    crear: "Plan/CrearPlan",
    editar: "Plan/EditarPlan",
    eliminar: "Plan/EliminarPlan",
    listar: "Plan/ListarPlanes"
  },
  renderRow: (ej) => `
    <tr id="tr-${ej.id}">
      <td>${ej.pnombre}</td>
      <td>${ej.descripcion}</td>
      <td>${ej.nombre}</td>
      
      <td>${ej.repeticiones}</td>
      <td>${ej.series}</td>
      <td>${ej.peso}</td>
      <td>
        <button class="btn-edit" onclick="editEjercicio(${ej.id})">Editar</button>
        <button class="btn-delete" onclick="deleteEjercicio(${ej.id})">Eliminar</button>
      </td>
    </tr>
  `
});
