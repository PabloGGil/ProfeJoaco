<?php include "./Vista/header.php" ?> 
    
    <main>
        <div class="container">
            <div class="form-container">
                <h2>Pagina principal</h2>
                <div class="carousel-container">
                <div class="carousel">
                <div class="carousel-item">
                    <img src="./Imagenes/5.jpg" alt="Paisaje montañoso">
                    <div class="carousel-caption">
                        <h3>Montañas Serenas</h3>
                        <p>Un paisaje tranquilo en la cordillera</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="./Imagenes/4.jpg" alt="Bosque nevado">
                    <div class="carousel-caption">
                        <h3>Bosque Invernal</h3>
                        <p>Árboles cubiertos de nieve en un día frío</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="./Imagenes/3.jpg" alt="Atardecer en la montaña">
                    <div class="carousel-caption">
                        <h3>Atardecer Dorado</h3>
                        <p>La puesta de sol tiñe el mar de colores cálidos</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="./Imagenes/1.jpg" alt="Paisaje natural">
                    <div class="carousel-caption">
                        <h3>Horizonte Infinito</h3>
                        <p>Un vasto paisaje bajo un cielo despejado</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="./Imagenes/2.jpg" alt="Bosque verde">
                    <div class="carousel-caption">
                        <h3>Bosque Encantado</h3>
                        <p>Senderos entre árboles centenarios</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="image-counter">
            <span id="current-slide">1</span> / <span id="total-slides">5</span>
        </div>
        
        <div class="carousel-controls">
            <button class="carousel-btn prev-btn">&#10094;</button>
            <button class="carousel-btn next-btn">&#10095;</button>
        </div>
        
        <div class="carousel-indicators">
            <!-- Los indicadores se generarán con JavaScript -->
        </div>
            </div>
        </div>
    </main>
    
    <footer>
       <div class="container">
            <div class="footer-content">
                <h3>Contáctanos</h3>
                <div class="footer-contact">
                    <div class="contact-item">
                        <a href="mailto:contacto@miempresa.com">
                            <span class="contact-icon">✉️</span>
                            joacogabriel02@gmail.com
                        </a>
                    </div>
                    <div class="contact-item">
                        <a href="https://www.instagram.com/miempresa" target="_blank">
                            <span class="contact-icon">📷</span>
                            @joacogabriel02
                        </a>
                    </div>
                </div>
                <div class="footer-copyright">
                    <p>&copy; 2025 Profe Joaquin. Todos los derechos reservados.</p>
                </div>
            </div>
        </div>
    </footer>
    
    <script src="js/Carrusel.js">   </script>
    <!-- <script src="js/validar.js">   </script> -->
</body>
</html>