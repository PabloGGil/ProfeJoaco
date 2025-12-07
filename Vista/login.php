<?php include "header.php" ?>
    
    <main>
        <div class="container">
            <div class="form-container">
                <h2>Ingreso</h2>
                <form id="userForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="firstName">Correo</label>
                            <input type="text" id="correo" name="correo" placeholder="Nombre de usuario/correo" required>
                        </div>
                        <div class="form-group">
                            <label for="lastName">Contraseña</label>
                            <input type="text" id="password" name="password" placeholder="Ingrese su contraseña" required>
                        </div>
                    </div>
                    
                    <!-- <div class="form-group">
                        <label for="birthDate">Fecha de Nacimiento</label>
                        <input type="date" id="birthDate" name="birthDate" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="comments">Comentarios</label>
                        <textarea id="comments" name="comments" placeholder="Ingrese cualquier comentario adicional..."></textarea>
                    </div> -->
                    
                    <button type="submit" class="btn-submit">Enviar</button>
                </form>
            </div>
        </div>
    </main>
    
 <?php include "footer.php" ?>