

import{setupCrud } from './admin.js';


setupCrud({
  entity: "Planes",
  fields: ["plan", "descripcion", "musculo","ejercicio"],
  columnas: ["Plan", "Descripcion", "Musculo","Ejercicio"],
  endpoints: {
    crear: "Plan/CrearPlan",
    editar: "Plan/EditarPlan",
    eliminar: "Plan/EliminarPlan",
    listar: "Plan/ListarPlanes"
  },
  renderRow: (ej) => `
    <tr id="tr-${ej.id}">
      <td>${ej.nombre}</td>
      <td>${ej.descripcion}</td>
      <td>${ej.musculo}</td>
      <td>${ej.ejercicio}</td>
      <td>
        <button class="btn-edit" onclick="editEjercicio(${ej.id})">Editar</button>
        <button class="btn-delete" onclick="deleteEjercicio(${ej.id})">Eliminar</button>
      </td>
    </tr>
  `
});
