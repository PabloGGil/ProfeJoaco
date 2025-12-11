<?php  include "header.php" ?>
   
    <main>
        <div class="container">
            <!-- Formulario para agregar/editar usuario -->
            <section id="user-form-section" class="user-form hidden ">
                <div class="form-container">
                    <h2 id="form-title">Agregar Nuevo Ejercicio</h2>
                    
                    <form id="user-form">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="musculo">Musculo</label>
                                <input type="text" id="musculo" name="musculo" required>
                            </div>
                            <div class="form-group">
                                <label for="ejercicio">Ejercicio</label>
                                <input type="text" id="ejercicio" name="ejercicio" required>
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
            
            <!-- Lista de usuarios -->
           
                 <section id="users-list-section" class="users-list ">
                <div class="form-container">
                    <h2>Lista de Ejercicios</h2>
                    <button  class="btn-submit" id="add-btn">Agregar Ejercicio</button>
                    <div id="users-container">
                        <!-- Los usuarios se cargarán aquí dinámicamente -->
                    </div>
                </div>
            </section>
        </div>
    </main>
    <script src="../js/AdminEjercicios.js"></script> 
<?php include "footer.php" ?>    

 