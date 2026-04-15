    import{PostData,getData } from '../Api.js';
    import{setupCrud} from  '../admin.js'
    import {planesConfig} from '../Config/PlanesConfig.js';
    const containerError = document.getElementById('container-error');
    const ejerciciosData=await getData(planesConfig.endpoints.listarEj);
    const enviarBtn = document.getElementById('submit-btn');
    const indicadorCarga = document.getElementById('indicador-carga');
    const listaContainer = document.getElementById('container-data');
    let isInitialized = false; 
 
    let ejerciciosSeleccionados = new Map();
    let ejerciciosFiltrados = [...ejerciciosData.data];
    enviarBtn.addEventListener('click', guardarPlan);
 
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            // cargarEjercicios();
        });
    } else {
            // cargarEjercicios();
    }
    

    async function ListarPlanes() {
      // Ocultar loading
      console.log("---arranca listarPlanes ---");
      indicadorCarga.style.display = 'none';
      const rta = await getData(planesConfig.endpoints.listar);
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
          <table class="tabla" id="tabla-${planesConfig.entity}">                
          <thead>
            <tr>
              ${planesConfig.columnas.map(f => `<th>${f}</th>`).join('')}
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
            <tr  data-${planesConfig.entity}="${plan.pnombre}" id='tr-${plan.id}'>
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
                <tr  data-${planesConfig.entity}="${plan.pnombre}" id='tr-${plan.id}'>
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
                <tr  data-${planesConfig.entity}="${plan.pnombre}">
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

    function editPlanes(fila) {
        // const row = document.querySelector(`#tr-${id}`);
        const datos = Array.from(fila.cells).map(td => td.innerText);
        currentEditingId = id;
        // if(config.entity=="Planes"){
        // tituloFormulario.textContent = `Editar ${entity}`;
        // enviarBtn.textContent = `Actualizar ${entity}`;
        // cancelarBtn.style.display = 'block';
        // toggleEjercicio(id);
    
    }

    function cargarEjercicios() {
        const container = document.getElementById('ejerciciosContainer');
        container.innerHTML = '';

        ejerciciosFiltrados.forEach(ejercicio => {
            console.log(ejercicio);
            const seleccionado = ejerciciosSeleccionados.has(ejercicio.id);
            const ejercicioHTML = crearEjercicioCard(ejercicio, seleccionado);
            container.innerHTML += ejercicioHTML;
        });

        // Actualizar contadores y resumen
        actualizarResumen();
    }

    function crearEjercicioCard(ejercicio, seleccionado = false) {
        const seleccion = ejerciciosSeleccionados.get(ejercicio.id) || { series: 3, repeticiones: 10, peso: '' };
        
        return `
            <div class="col-md-6 col-lg-4 mb-3 ejercicio-card-wrapper" data-id="${ejercicio.id}">
                <div class="card ejercicio-card ${seleccionado ? 'seleccionado' : ''}" 
                        onclick="toggleEjercicio(${ejercicio.id})">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">${ejercicio.nombre}</h6>
                        <span class="badge-grupo">${ejercicio.grupo_muscular}</span>
                    </div>
                    <div class="card-body">
                        <p class="small text-muted mb-2">${ejercicio.descripcion}</p>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="badge bg-info">${ejercicio.categoria}</span>
                            <span class="badge bg-secondary">${ejercicio.dificultad}</span>
                        </div>
                        
                        <!-- Detalles del ejercicio (visible solo cuando está seleccionado) -->
                        <div class="detalles-ejercicio ${seleccionado ? '' : 'd-none'}" 
                                data-detalles-id="${ejercicio.id}">
                            <hr>
                            <div class="row g-2">
                                <div class="col-4">
                                    <label class="form-label small">Series</label>
                                    <input type="number" class="form-control form-control-sm" 
                                            value="${seleccion.series}" min="1" max="10"
                                            onclick="event.stopPropagation()"
                                            onchange="actualizarDetalleEjercicio(${ejercicio.id}, 'series', this.value)">
                                </div>
                                <div class="col-4">
                                    <label class="form-label small">Reps</label>
                                    <input type="number" class="form-control form-control-sm" 
                                            value="${seleccion.repeticiones}" min="1" max="50"
                                            onclick="event.stopPropagation()"
                                            onchange="actualizarDetalleEjercicio(${ejercicio.id}, 'repeticiones', this.value)">
                                </div>
                                <div class="col-4">
                                    <label class="form-label small">Peso (kg)</label>
                                    <input type="number" class="form-control form-control-sm" 
                                            value="${seleccion.peso}" min="0" step="0.5"
                                            onclick="event.stopPropagation()"
                                            onchange="actualizarDetalleEjercicio(${ejercicio.id}, 'peso', this.value)">
                                </div>
                            </div>
                            <div class="mt-2">
                                <label class="form-label small">Notas</label>
                                <input type="text" class="form-control form-control-sm" 
                                        placeholder="Notas opcionales"
                                        value="${seleccion.notas || ''}"
                                        onclick="event.stopPropagation()"
                                        onchange="actualizarDetalleEjercicio(${ejercicio.id}, 'notas', this.value)">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    window.toggleEjercicio=function toggleEjercicio(ejercicioId){//}, event) {
        // Evitar que el clic en inputs afecte la selección
        
        
        // if (event.target.tagName === 'INPUT') return;

        const wrapper = document.querySelector(`.ejercicio-card-wrapper[data-id="${ejercicioId}"]`);
        const card = wrapper.querySelector('.ejercicio-card');
        const detalles = card.querySelector('.detalles-ejercicio');
        
        if (ejerciciosSeleccionados.has(ejercicioId)) {
            // Deseleccionar
            ejerciciosSeleccionados.delete(ejercicioId);
            card.classList.remove('seleccionado');
            detalles.classList.add('d-none');
        } else {
            // Seleccionar con valores por defecto
            ejerciciosSeleccionados.set(ejercicioId, {
                series: 3,
                repeticiones: 10,
                peso: '',
                notas: ''
            });
            card.classList.add('seleccionado');
            detalles.classList.remove('d-none');
        }
        
        actualizarResumen();
    }

    window.actualizarDetalleEjercicio=(ejercicioId, campo, valor)=> {
        if (ejerciciosSeleccionados.has(ejercicioId)) {
            const ejercicio = ejerciciosSeleccionados.get(ejercicioId);
            ejercicio[campo] = campo === 'peso' ? (valor ? parseFloat(valor) : '') : valor;
            ejerciciosSeleccionados.set(ejercicioId, ejercicio);
            actualizarResumen();
        }
    }

    function actualizarResumen() {
        const total = ejerciciosSeleccionados.size;
        document.getElementById('totalEjercicios').textContent = total;
        
        const resumenContainer = document.getElementById('resumenEjercicios');
        
        if (total === 0) {
            resumenContainer.innerHTML = '<p class="text-muted text-center">No hay ejercicios seleccionados</p>';
            return;
        }
        console.log("ojotaaa---");
        for (let [key, value] of ejerciciosSeleccionados) {
            console.log(key, value); // a 1, b 2, c 3
        }
        let resumenHTML = '';
        console.log(ejerciciosData.data);           
        ejerciciosSeleccionados.forEach((detalles, ejercicioId) => {
            console.log("ejercicioID"+ejercicioId);
            const ejercicio = ejerciciosData.data.find(e => e.id == ejercicioId);
            console.log(ejercicio.id);
            if (ejercicio) {
                resumenHTML += `
                    <div class="resumen-item">
                        <div class="d-flex justify-content-between">
                            <strong>${ejercicio.nombre}</strong>
                            <span class="badge bg-success">${detalles.series}x${detalles.repeticiones}</span>
                        </div>
                        ${detalles.peso ? `<small>Peso: ${detalles.peso} kg</small>` : ''}
                        ${detalles.notas ? `<br><small class="text-muted">📝 ${detalles.notas}</small>` : ''}
                    </div>
                `;
            }
        });
        
        resumenContainer.innerHTML = resumenHTML;
    }
 
    window.seleccionarTodos=()=> {
        ejerciciosFiltrados.forEach(ejercicio => {
            if (!ejerciciosSeleccionados.has(ejercicio.id)) {
                ejerciciosSeleccionados.set(ejercicio.id, {
                    series: 3,
                    repeticiones: 10,
                    peso: '',
                    notas: ''
                });
            }
        });
        cargarEjercicios();
    }

    window.deseleccionarTodos=()=> {
        ejerciciosSeleccionados.clear();
        cargarEjercicios();
    }

    async function guardarPlan(event) {
            // event.preventDefault();
        // event.stopPropagation();
            const nombrePlan = document.getElementById('nombrePlan').value;
            if (!nombrePlan) {
                
                alert('Por favor, ingresa un nombre para el plan');
                return false;
            }

            if (ejerciciosSeleccionados.size === 0) {
                alert('Debes seleccionar al menos un ejercicio');
                return false;
            }

            // Preparar datos del plan
            const planData = {
                nombre: nombrePlan,
                descripcion: document.getElementById('descripcionPlan').value,
                fechaInicio: document.getElementById('fechaInicio').value,
                ejercicios: []
            };

            ejerciciosSeleccionados.forEach((detalles, ejercicioId) => {
                const ejercicio = ejerciciosData.data.find(e => e.id == ejercicioId);
                planData.ejercicios.push({
                    id: ejercicioId,
                    nombre: ejercicio.nombre,
                    ...detalles
                });
            });

            
            console.log('Plan a guardar:', planData);
            
            let rta = await PostData(planesConfig.endpoints.crear, planData);
            if (!rta.success) {
                console.log("error");
                MostrarMensaje(rta.errorMessage, rta.errorCode);
                // debugger;
                return rta;
            }
            alert("Plan agregado correctamente");
            console.log(window.location.href);
            // if (MostrarSeccion) {
            MostrarSeccion('list');
    // }
    // if (window.ListarPlanes) {
        ListarPlanes();
    // }
            // return true;
        }

    export function initPlanesModule() {
       
        const planesHandler= {
            onInit:null,
            onList: ListarPlanes,
            onShowEj: cargarEjercicios,
            onCreate: guardarPlan,
            onDelete: editPlanes,
            onUpdate: guardarPlan,
        };
        console.log("Initializing Planes module...");
        setupCrud(planesConfig, planesHandler);
    }
 // edicion(22);