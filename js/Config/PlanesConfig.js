

// import{setupCrud } from '../admin.js';


export const planesConfig={
  entity: "Planes",
  fields: ["pnombre", "descripcion", "nombre", "grupo_muscular","repeticiones", "series", "peso"],
  columnas: ["Plan", "Descripcion", "ejercicio", "Musculo","repeticiones", "series", "peso"],
  endpoints: {
    crear: "Plan/CrearPlan",
    editar: "Plan/EditarPlan",
    eliminar: "Plan/EliminarPlan",
    listar: "Plan/ListarPlanes",
    listarEj: "Ejercicio/ListarEjercicios",
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
        <button class="btn-edit" onclick="editPlanes(${ej.pnombre})">Editar</button>
        <button class="btn-delete" onclick="deletePlanes(${ej.pnombre})">Eliminar</button>
      </td>
    </tr>
  `
};
