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
                    <h2 id="form-title">Lista de Usuarios</h2>
                    
                    <form id="formulario">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" id="nombre" name="nombre" required>
                            </div>
                            <div class="form-group">
                                <label for="apellido">Apellido</label>
                                <input type="text" id="apellido" name="apellido" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="correo">Correo Electrónico</label>
                            <input type="email" id="correo" name="correo" required>
                        </div>
                        <div class="form-group">
                            <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                             <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required >
                        </div>
                        <div class="form-group">
                            <label for="comments">Comentarios</label>
                            <textarea id="comentario" name="comentario" placeholder="Ingrese cualquier comentario adicional..."></textarea>
                        </div>
                        <button type="button" class="btn-submit" id="submit-btn">Enviar</button>
                        <button type="button" class="btn-submit" id="cancel-btn" style="background: #6c757d; margin-top: 10px; ">Cancelar</button>
                    </form>
                </div>
            </section>
            
            <!-- Lista  -->
           
                <section id="list-section" class="lista ">
                <div class="form-container">
                    <h2>Lista de Usuarios</h2>
                    <div class="filtro-container">
                        <input type="text" 
                            class="form-control" 
                            id="filtro" 
                            placeholder="Filtrar por nombre de usuario(correo)...">
                        <button class="btn btn-secondary" onclick="limpiarFiltro()">Limpiar</button>
                    <button  class="btn-submit" id="add-btn">Agregar Usuario</button>
                    <div id="container-data">
                        <!-- Los objetos se cargarán aquí dinámicamente -->
                    </div>
                </div>
            </section>
        </div>
    </main>
    <script  type="module" src="../js/main.js"></script> 
<?php include "footer.php" ?>    
