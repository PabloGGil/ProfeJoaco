<?php include "header.php" ; ?>

    <!-- <a href="../Vista/Logout.php"></a> -->
    <main>
        <div class="container">
            <div class="form-container">
                <h2>Ingreso</h2>
                <form id="formCambioPasswd">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="firstName">Contraseña</label>
                            <input type="password" id="passwd" name="passwd" placeholder="Ingrese su contraseña" required>
                        </div>
                        <div class="form-group">
                            <label for="lastName">Confirme Contraseña</label>
                            <input type="password" id="re-passwd" name="re-password" placeholder="Repita la contraseña" required>
                        </div>
                    </div>
                    
                                   
                    <button id='btn-cambio-passwd' class="btn btn-submit">Enviar</button>
                </form>
            </div>
        </div>
    </main>
    <script type='module' src='../js/Login.js'></script>
    
 <?php include "footer.php" ?>