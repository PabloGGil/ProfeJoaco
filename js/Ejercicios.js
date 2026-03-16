

import{setupCrud } from './admin.js';


setupCrud({
  entity: "Ejercicio",
  fields: ["grupomuscular", "nombre","categoria","dificultad", "explicacion"],
  columnas: ["Musculo", "Ejercicio" ,"Categoria", "Dificultad"],
  endpoints: {
    crear: "Ejercicio/CrearEjercicio",
    editar: "Ejercicio/EditarEjercicio",
    eliminar: "Ejercicio/EliminarEjercicio",
    listar: "Ejercicio/ListarEjercicios"
  },
  renderRow: (ej) => `
    <tr id="tr-${ej.id}">
      <td>${ej.grupomuscular}</td>
      <td>${ej.nombre}</td>
      <td>${ej.categoria}</td>
      <td>${ej.dificultad}</td>
      
      <td>
        <button class="btn-detalle" onclick="detalleEjercicio(${ej.id})">Detalle</button>
        <button class="btn-edit" onclick="editEjercicio(${ej.id})">Editar</button>
        <button class="btn-delete" onclick="deleteEjercicio(${ej.id})">Eliminar</button>
      </td>
    </tr>
  `
});
