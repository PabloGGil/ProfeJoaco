// import{setupCrud } from '../admin.js';
const NombreEntidad = "Ejercicio";

export const ejerciciosConfig={
  entity: NombreEntidad,
  fields: ["grupo_muscular", "nombre","categoria","dificultad", "descripcion"],
  columnas: ["Musculo", "Ejercicio" ,"Categoria", "Dificultad"],
  endpoints: {
    crear: "Ejercicio/CrearEjercicio",
    editar: "Ejercicio/EditarEjercicio",
    eliminar: "Ejercicio/EliminarEjercicio",
    listar: "Ejercicio/ListarEjercicios"
  },
  renderRow: (ej) => `
    <tr data-${NombreEntidad}="${ej.grupo_muscular}" id="tr-${ej.id}">
      <td>${ej.grupo_muscular}</td>
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
};
