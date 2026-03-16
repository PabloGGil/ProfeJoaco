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
                        <button type="button" class="btn-submit" id="submit-btn">Enviar</button>
                        <button type="button" class="btn-submit" id="cancel-btn" style="background: #6c757d; margin-top: 10px; ">Cancelar</button>
                    </form>
                </div>
            </section>
 

    
            <!-- Lista  -->
           
            <section id="list-section" class="lista ">
                <div class="form-container">
                    <h2>Lista de Planes</h2>
                    <button  class="btn-submit" id="add-btn">Agregar Plan</button>
                    <div id="container-data">
                        <!-- Los usuarios se cargarán aquí dinámicamente -->
                    </div>
                </div>
            </section>
        </div>
    </main>
    <script type="module" src="../js/Planes.js"></script> 
   
<?php include "footer.php" ?>    

 