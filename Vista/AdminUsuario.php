<?php  include "header.php" ?>
   
    <main>
        <div class="container">
            <!-- Formulario para agregar/editar usuario -->
            <section id="user-form-section" class="user-form hidden ">
                <div class="form-container">
                    <h2 id="form-title">Agregar Nuevo Usuario</h2>
                    
                    <form id="user-form">
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
                            <label for="fechaNacimiento">Fecha de Nacimiento</label>
                             <input type="date" id="fechaNacimiento" name="fechaNacimiento" required >
                        </div>
                        <button type="button" class="btn-submit" id="submit-btn">Enviar</button>
                        <button type="button" class="btn-submit" id="cancel-btn" style="background: #6c757d; margin-top: 10px; ">Cancelar</button>
                    </form>
                </div>
            </section>
            
            <!-- Lista de usuarios -->
           
                 <section id="users-list-section" class="users-list ">
                <div class="form-container">
                    <h2>Lista de Usuarios</h2>
                    <button  class="btn-submit" id="add-btn">Agregar Usuario</button>
                    <div id="users-container">
                        <!-- Los usuarios se cargarán aquí dinámicamente -->
                    </div>
                </div>
            </section>
        </div>
    </main>
    <script src="../js/AdminUsuario.js"></script> 
<?php include "footer.php" ?>    
 