import { setupCrud } from "./admin.js";

setupCrud({
  entity: "Usuario",
  fields: ["nombre", "apellido", "correo", "fechanac","comentario"],
  columnas:["Nombre", "Apellido", "Correo", "Fecha Nacimiento","Edad","comentario"],
  endpoints: {
    crear: "Usuario/CrearUsuario",
    editar: "Usuario/EditarUsuario",
    eliminar: "Usuario/EliminarUsuario",
    listar: "Usuario/ListarUsuarios"
  },
  renderRow: (user) => `
    <tr id="tr-${user.id}">
      <td>${user.nombre}</td>
      <td>${user.apellido}</td>
      <td>${user.correo}</td>
      <td>${new Date(user.fechanac).toLocaleDateString('es-ES')}</td>
      <td>${calcularEdad(user.fechanac)}</td>
      <td>${user.comentario}</td>
      <td>
        <button class="btn-edit" onclick="editUsuario(${user.id})">Editar</button>
        <button class="btn-delete" onclick="deleteUsuario(${user.id})">Eliminar</button>
      </td>
    </tr>
  `
});

function calcularEdad(fechaNacimiento) {
    const hoy = new Date();
    const nacimiento = new Date(fechaNacimiento);
    
    let edad = hoy.getFullYear() - nacimiento.getFullYear();
    const mes = hoy.getMonth() - nacimiento.getMonth();
    
    // Si aún no ha pasado el mes de cumpleaños este año, restar 1
    if (mes < 0 || (mes === 0 && hoy.getDate() < nacimiento.getDate())) {
        edad--;
    }
    
    return edad;
}