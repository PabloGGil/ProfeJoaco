<?php  include "header.php" ?>
   
    <main>
       <div class="container_error hidden" id="container-error">    
            <div id=mensaje>
    
            </div>
        </div>
        <div class="container">
            <!-- Formulario para agregar/editar plan -->
            <section id="form-section" class="formulario hidden ">
                <div class="form-container">
                    <h2 id="form-title">Agregar Nuevo Plan</h2>
                    <form id="formulario">
                        <!-- <form id="planForm" onsubmit="return guardarPlan(event)"> -->
                
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
                                        <button type="submit" class="btn btn-success" id="submit-btn">
                                            <i class="fas fa-save"></i> Guardar Plan
                                        </button>
                                        <button type="button" class="btn-submit" id="cancel-btn" style="background: #6c757d; margin-top: 10px; ">Cancelar</button>
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
            </section>
 

    
            <!-- Lista  -->
           
            <section id="list-section" class="lista ">
                <div class="form-container">
                    <h2>Lista de Planes</h2>
                    <div class="filtro-container">
                        <input type="text" 
                            class="form-control" 
                            id="filtro" 
                            placeholder="Filtrar por nombre de plan...">
                        <button class="btn btn-secondary" onclick="limpiarFiltro()">Limpiar</button>
                    </div>
                    <div id="tabla-container">
                        <div class="loading" id="indicador-carga">
                            <span>Cargando datos...</span>
                        </div>
                    </div>
                    <!-- <button  class="btn-submit" id="add-btn"> -->
                        <!-- <a href="CrearPlan.php" class="btn-submit" id="add-btn">Agregar Plan</a> -->
                         <button  class="btn-submit" id="add-btn">Agregar Plan</button>
                     <!-- </button>  -->
                    <div id="container-data">
                        <!-- Los usuarios se cargarán aquí dinámicamente -->
                    </div>
                </div>
            </section>
        </div>
    </main>
    <script type="module" src="../js/main.js"></script> 
   
<?php include "footer.php" ?>    

 