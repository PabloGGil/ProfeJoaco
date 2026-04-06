<?php  include "header.php" ?>
   
    <main>
       <div class="container_error hidden" id="container-error">    
            <div id=mensaje>
    
            </div>
        </div>
        <div class="container">
            <!-- Formulario para agregar/editar usuario -->
            <section id="form-section" class="formulario hidden ">
                <div class="form-container">
                    <h2 id="form-title">Agregar Nuevo Ejercicio</h2>
                    
                    <form id="formulario">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="grupo_muscular">Musculo</label>
                                <input type="text" id="grupo_muscular" name="grupo_muscular" placeholder="Musculo ó grupo muscular" required>
                            </div>
                            <div class="form-group">
                                <label for="nombre">Ejercicio</label>
                                <input type="text" id="nombre" name="nombre" placeholder="Nombre del ejercicio" required>
                            </div>
                        </div>
                        <div class="form-row g-3 ">
                            <div class="form-group">
                                <div class="col-md-6">    
                                    <label for="categoria">Categoria</label>
                                    <select class="form-select" id="categoria" name="categoria" >
                                        <option value="Fuerza">Fuerza</option>
                                        <option value="Funcional">Funcional</option>
                                        <option value="Cardio">Cardio</option>
                                        <option value="Flexibilidad">Flexibilidad</option>
                                    </select>
                                </div>
                                <!-- <input type="text" id="categoria" name="categoria" placeholder="Fuerza, cardio, funcional, etc" required> -->
                            </div>
                            <div class="form-group">
                                <div class="col-md-3">    
                                    <label for="dificultad">Dificultad</label>
                                    <select class="form-select" id="dificultad" name="dificultad">
                                        <option value="Basico">Basico</option>
                                        <option value="Intermedio">Intermedio</option>
                                        <option value="Avanzado">Avanzado</option>
                                    </select>
                                </div>    
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="explicacion">Breve Explicacion</label>
                            <!-- <input type="text" id="explicacion" name="explicacion" required> -->
                             <textarea id="explicacion" name="explicacion" rows="4" cols="50" placeholder="Escribe aquí tu comentario de varias líneas..."></textarea><br>
                        </div>
                       
                        <button type="button" class="btn-submit" id="submit-btn">Enviar</button>
                        <button type="button" class="btn-submit" id="cancel-btn" style="background: #6c757d; margin-top: 10px; ">Cancelar</button>
                    </form>
                </div>
            </section>
            
            <!-- Lista  -->
           
            <section id="list-section" class="lista ">
                <div class="form-container">
                    <h2>Lista de Ejercicios</h2>
                    <div class="filtro-container">
                        <input type="text" 
                            class="form-control" 
                            id="filtroEjercicio" 
                            placeholder="Filtrar por nombre de plan...">
                        <button class="btn btn-secondary" onclick="limpiarFiltro()">Limpiar</button>
                    </div>
                    <button  class="btn-submit" id="add-btn">Agregar Ejercicio</button>
                    <div id="container-data">

                        <!-- Los usuarios se cargarán aquí dinámicamente -->
                    </div>
                </div>
            </section>
        </div>
    </main>
    <script type="module" src="../js/Ejercicios.js"></script> 
<?php include "footer.php" ?>    

 