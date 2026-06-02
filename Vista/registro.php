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
                            <input type="text" id="nombre" name="firstName" placeholder="Ingrese su nombre" required >
                        </div>
                        <div class="form-group">
                            <label for="lastName">Apellido</label>
                            <input type="text" id="apellido" name="lastName" placeholder="Ingrese su apellido" required>
                        </div>
                        <div class="form-group">
                            <label for="correo">Correo</label>
                            <input type="email" id="correo" name="correo" placeholder="Ingrese su correo electronico" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="fechanac">Fecha de Nacimiento</label>
                        <input type="date" id="fechanac" name="fechanac" >
                    </div>
                    
                    <div class="form-group">
                        <label for="comentarios">Comentarios</label>
                        <textarea id="comentarios" name="comentarios" placeholder="Ingrese cualquier comentario adicional..."></textarea>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="passwd">Contraseña</label>
                            <input type="password" id="passwd" name="passwd" placeholder="Ingrese contraseña" required>
                        </div>
                        <div class="form-group">
                            <label for="passwd">Confirmacion contraseña</label>
                            <input type="password" id="re-passwd" name="passwd" placeholder="Ingrese contraseña" required>
                        </div>
                    </div>
                    <button class="btn-submit" id="btn-registro">Registrar Usuario</button>
                </form>
            </div>
        </div>
    </main>
    <!-- <script src="../js/validar.js"></script> -->
    <script type="module" src="../js/validar.js"></script>
  <?php  include "footer.php" ?>