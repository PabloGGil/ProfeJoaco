    import{PostData,getData } from './Api.js';
    // import{setupCrud } from './admin.js';
    
    // Datos de ejemplo - En un caso real, estos vendrían de una API o base de datos
        // const ejerciciosData = [
        //     { id: 1, nombre: "Press de Banca", categoria: "Fuerza", grupoMuscular: "Pecho", descripcion: "Ejercicio básico para pecho", dificultad: "Intermedio" },
        //     { id: 2, nombre: "Sentadilla", categoria: "Fuerza", grupoMuscular: "Piernas", descripcion: "Ejercicio fundamental para piernas", dificultad: "Básico" },
        //     { id: 3, nombre: "Dominadas", categoria: "Fuerza", grupoMuscular: "Espalda", descripcion: "Ejercicio para espalda y bíceps", dificultad: "Avanzado" },
        //     { id: 4, nombre: "Press Militar", categoria: "Fuerza", grupoMuscular: "Hombros", descripcion: "Desarrollo de hombros", dificultad: "Intermedio" },
        //     { id: 5, nombre: "Curl de Bíceps", categoria: "Fuerza", grupoMuscular: "Brazos", descripcion: "Aislamiento para bíceps", dificultad: "Básico" },
        //     { id: 6, nombre: "Correr", categoria: "Cardio", grupoMuscular: "Full Body", descripcion: "Ejercicio cardiovascular", dificultad: "Básico" },
        //     { id: 7, nombre: "Peso Muerto", categoria: "Fuerza", grupoMuscular: "Espalda", descripcion: "Ejercicio compuesto completo", dificultad: "Avanzado" },
        //     { id: 8, nombre: "Plancha", categoria: "Funcional", grupoMuscular: "Abdomen", descripcion: "Estabilización del core", dificultad: "Intermedio" },
        //     { id: 9, nombre: "Fondos", categoria: "Fuerza", grupoMuscular: "Brazos", descripcion: "Desarrollo de tríceps", dificultad: "Intermedio" },
        //     { id: 10, nombre: "Zancadas", categoria: "Funcional", grupoMuscular: "Piernas", descripcion: "Trabajo unilateral de piernas", dificultad: "Básico" }
        // ];
        const ejerciciosData=await getData("Ejercicio/ListarEjercicios");
        // console.log(ejerciciosData.data);
        console.log(ejerciciosData);
        // Estado de la aplicación
        let ejerciciosSeleccionados = new Map();
        let ejerciciosFiltrados = [...ejerciciosData.data];
        // let ejerciciosFiltrados = [...ejerciciosData];
        console.log(ejerciciosFiltrados);
        // Inicialización
        // document.addEventListener('DOMContentLoaded', function() {
        //     cargarEjercicios();
        //     inicializarEventListeners();
        // });
        function iniciar() {
            console.log("Iniciando aplicación");
            cargarEjercicios();
            inicializarEventListeners();
        }

        if (document.readyState === 'loading') {
            console.log("DOM aún cargando, esperando evento");
            document.addEventListener('DOMContentLoaded', iniciar);
        } else {
            console.log("DOM ya cargado, ejecutando inmediatamente");
            iniciar();
        }

        function inicializarEventListeners() {
            // Filtros
            document.getElementById('buscarEjercicio').addEventListener('input', window.aplicarFiltros);
            document.getElementById('filtroCategoria').addEventListener('change', window.aplicarFiltros);
            document.getElementById('filtroGrupoMuscular').addEventListener('change', window.aplicarFiltros);
            document.getElementById('btnGuardar').addEventListener('click', guardarPlan);
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
                         onclick="toggleEjercicio(${ejercicio.id}, event)">
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

        window.toggleEjercicio=function toggleEjercicio(ejercicioId, event) {
            // Evitar que el clic en inputs afecte la selección
            if (event.target.tagName === 'INPUT') return;

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

        window.aplicarFiltros=()=> {
            const busqueda = document.getElementById('buscarEjercicio').value.toLowerCase();
            const categoria = document.getElementById('filtroCategoria').value;
            const grupoMuscular = document.getElementById('filtroGrupoMuscular').value;

            ejerciciosFiltrados = ejerciciosData.data.filter(ejercicio => {
                let cumpleBusqueda = !busqueda || 
                    ejercicio.nombre.toLowerCase().includes(busqueda) ||
                    ejercicio.descripcion.toLowerCase().includes(busqueda);
                
                let cumpleCategoria = !categoria || ejercicio.categoria === categoria;
                let cumpleGrupo = !grupoMuscular || ejercicio.grupoMuscular === grupoMuscular;
                
                return cumpleBusqueda && cumpleCategoria && cumpleGrupo;
            });

            cargarEjercicios();
        }

        window.limpiarFiltros=()=> {
            document.getElementById('buscarEjercicio').value = '';
            document.getElementById('filtroCategoria').value = '';
            document.getElementById('filtroGrupoMuscular').value = '';
            ejerciciosFiltrados = [...ejerciciosData.data];
            cargarEjercicios();
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
            event.preventDefault();
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
                // console.log(detalles);
                // console.log(ejercicioId);
                const ejercicio = ejerciciosData.data.find(e => e.id == ejercicioId);
                planData.ejercicios.push({
                    id: ejercicioId,
                    nombre: ejercicio.nombre,
                    ...detalles
                });
            });

            // Mostrar modal de confirmación
            document.getElementById('modalTotalEjercicios').textContent = ejerciciosSeleccionados.size;
            
            // let detalleModal = '';
            // planData.ejercicios.forEach(ej => {
            //     detalleModal += `<div class="mb-1">• ${ej.nombre}: ${ej.series}x${ej.repeticiones} ${ej.peso ? '| ' + ej.peso + 'kg' : ''}</div>`;
            // });
            // document.getElementById('modalDetalleEjercicios').innerHTML = detalleModal;

            // Aquí enviarías los datos a tu backend
            console.log('Plan a guardar:', planData);
debugger;
let rta = await PostData("Plan/CrearPlan", planData);
    if (!rta.success) {
      console.log("error");
        // MostrarMensaje(rta.errorMessage, rta.errorCode);
    //   return;
    }
            // const modal = new bootstrap.Modal(document.getElementById('confirmarModal'),{keyboard:false});
            // modal.show();
            
            // window.location.href='Plan/ListarPlanes'
            // return false;
        }

        function verPlanes() {
            // Redirigir a la lista de planes
            window.location.href = '/planes';
            alert('Redirigiendo a la lista de planes...');
        }

        window.crearEjercicioCard=crearEjercicioCard;
        window.toggleEjercicio=toggleEjercicio;
        window.actualizarDetalleEjercicio=actualizarDetalleEjercicio;