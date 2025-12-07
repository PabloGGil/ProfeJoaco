 <?php  include "header.php" ?>
    
    <main>
        <div class="container">
            <div class="form-container">
                <h2>Formulario de Registro</h2>
                <!-- <form action="/Controladores/UsuarioController.php" method="POST" id="userForm"> -->
                <form id="userForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="firstName">Nombre</label>
                            <input type="text" id="nombre" name="firstName" placeholder="Ingrese su nombre" >
                        </div>
                        <div class="form-group">
                            <label for="lastName">Apellido</label>
                            <input type="text" id="apellido" name="lastName" placeholder="Ingrese su apellido" >
                        </div>
                        <div class="form-group">
                            <label for="correo">Correo</label>
                            <input type="email" id="correo" name="correo" placeholder="Ingrese su correo electronico" >
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="birthDate">Fecha de Nacimiento</label>
                        <input type="date" id="fechaNacimiento" name="birthDate" >
                    </div>
                    
                    <div class="form-group">
                        <label for="comments">Comentarios</label>
                        <textarea id="comentarios" name="comments" placeholder="Ingrese cualquier comentario adicional..."></textarea>
                    </div>
                    
                    <!-- <button type="submit" class="btn-submit">Registrar Usuario</button> -->
                     <button class="btn-submit" onclick="registrarUsuario()">Registrar Usuario</button>
                </form>
            </div>
        </div>
    </main>
    <script src="../js/validar.js"></script>
    <!-- <script src="js/validar.js"> -->
  <?php  include "footer.php" ?>