<?php  include "header.php" ?>
<link rel="stylesheet" href="../css/CrearPlan.css"></link>
<!-- <script type="module" src="../js/CrearPlan.js"></script>  -->

<body class="bg-light">
    <div class="container py-4">
        <!-- Encabezado -->
        <div class="row mb-4">
            <div class="col">
                <h2 class="display-5">
                    <i class="fas fa-dumbbell text-primary"></i> 
                    Crear Plan de Entrenamiento
                </h1>
                <p class="lead">Selecciona los ejercicios que formarán parte de tu plan</p>
            </div>
        </div>

        <!-- Formulario principal -->
        <form id="planForm" onsubmit="return guardarPlan(event)">
            <!-- <form id="planForm" > -->
            <!-- Información básica del plan -->
            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-info-circle"></i> 
                                Información del Plan
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nombrePlan" class="form-label">Nombre del Plan *</label>
                                    <input type="text" class="form-control" id="nombrePlan" required 
                                           placeholder="Ej: Rutina de Fuerza, Full Body, etc.">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="fechaInicio" class="form-label">Fecha de Inicio</label>
                                    <input type="date" class="form-control" id="fechaInicio">
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="descripcionPlan" class="form-label">Descripción</label>
                                    <textarea class="form-control" id="descripcionPlan" rows="2" 
                                              placeholder="Describe el objetivo del plan..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Resumen de selección -->
                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-list"></i> 
                                Resumen del Plan
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <span class="contador-seleccion" id="totalEjercicios">0</span>
                                <span class="text-muted">ejercicios seleccionados</span>
                            </div>
                            <div id="resumenEjercicios" class="resumen-container" style="max-height: 300px; overflow-y: auto;">
                                <p class="text-muted text-center">No hay ejercicios seleccionados</p>
                            </div>
                            <hr>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-success" id="btnGuardar">
                                    <i class="fas fa-save"></i> Guardar Plan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtros y búsqueda -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-search"></i> 
                        Filtrar Ejercicios
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Buscar ejercicio</label>
                            <input type="text" class="form-control" id="buscarEjercicio" 
                                   placeholder="Nombre del ejercicio...">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Categoría</label>
                            <select class="form-select" id="filtroCategoria">
                                <option value="">Todas</option>
                                <option value="Fuerza">Fuerza</option>
                                <option value="Cardio">Cardio</option>
                                <option value="Flexibilidad">Flexibilidad</option>
                                <option value="Funcional">Funcional</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Grupo Muscular</label>
                            <select class="form-select" id="filtroGrupoMuscular">
                                <option value="">Todos</option>
                                <option value="Pecho">Pecho</option>
                                <option value="Espalda">Espalda</option>
                                <option value="Hombros">Hombros</option>
                                <option value="Brazos">Brazos</option>
                                <option value="Piernas">Piernas</option>
                                <option value="Abdomen">Abdomen</option>
                                <option value="Full Body">Full Body</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <button class="btn btn-secondary w-100" onclick="limpiarFiltros()">
                                <i class="fas fa-eraser"></i> Limpiar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de ejercicios -->
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-dumbbell"></i> 
                        Ejercicios Disponibles
                    </h5>
                    <div>
                        <button type="button" class="btn btn-light btn-sm" onclick="seleccionarTodos()">
                            <i class="fas fa-check-double"></i> Seleccionar Todos
                        </button>
                        <button type="button" class="btn btn-light btn-sm" onclick="deseleccionarTodos()">
                            <i class="fas fa-times"></i> Deseleccionar Todos
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row" id="ejerciciosContainer">
                        <!-- Los ejercicios se cargarán aquí dinámicamente -->
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal para confirmar guardado -->
    <div class="modal fade" id="confirmarModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-check-circle"></i> Plan Creado
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>¡El plan se ha creado exitosamente!</p>
                    <p><strong>Total de ejercicios:</strong> <span id="modalTotalEjercicios"></span></p>
                    <div id="modalDetalleEjercicios" class="small"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="verPlanes()">Ver todos los planes</button>
                </div>
            </div>
        </div>
    </div>
    <script type="module" src="../js/CrearPlan.js"></script>
    <!-- <script src="../js/Planes.js"></script> -->

   <?php include "footer.php" ?>  
