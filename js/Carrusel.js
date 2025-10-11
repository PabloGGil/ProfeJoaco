    document.addEventListener('DOMContentLoaded', function() {
            const carousel = document.querySelector('.carousel');
            const items = document.querySelectorAll('.carousel-item');
            const prevBtn = document.querySelector('.prev-btn');
            const nextBtn = document.querySelector('.next-btn');
            const indicatorsContainer = document.querySelector('.carousel-indicators');
            const currentSlideElement = document.getElementById('current-slide');
            const totalSlidesElement = document.getElementById('total-slides');
            
            let currentIndex = 0;
            const totalItems = items.length;
            
            // Configurar el total de slides
            totalSlidesElement.textContent = totalItems;
            
            // Crear indicadores
            for (let i = 0; i < totalItems; i++) {
                const indicator = document.createElement('div');
                indicator.classList.add('indicator');
                if (i === 0) indicator.classList.add('active');
                indicator.addEventListener('click', () => goToSlide(i));
                indicatorsContainer.appendChild(indicator);
            }
            
            // Función para actualizar el carrusel
            function updateCarousel() {
                carousel.style.transform = `translateX(-${currentIndex * 100}%)`;
                
                // Actualizar indicadores
                document.querySelectorAll('.indicator').forEach((indicator, index) => {
                    indicator.classList.toggle('active', index === currentIndex);
                });
                
                // Actualizar contador
                currentSlideElement.textContent = currentIndex + 1;
            }
            
            // Función para ir a un slide específico
            function goToSlide(index) {
                currentIndex = index;
                updateCarousel();
            }
            
            // Función para siguiente slide
            function nextSlide() {
                currentIndex = (currentIndex + 1) % totalItems;
                updateCarousel();
            }
            
            // Función para slide anterior
            function prevSlide() {
                currentIndex = (currentIndex - 1 + totalItems) % totalItems;
                updateCarousel();
            }
            
            // Event listeners para los botones
            nextBtn.addEventListener('click', nextSlide);
            prevBtn.addEventListener('click', prevSlide);
            
            // Navegación con teclado
            document.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowRight') nextSlide();
                if (e.key === 'ArrowLeft') prevSlide();
            });
            
            // Carrusel automático (opcional)
            let autoPlay = setInterval(nextSlide, 5000);
            
            // Pausar el carrusel automático al interactuar
            carousel.addEventListener('mouseenter', () => clearInterval(autoPlay));
            carousel.addEventListener('mouseleave', () => {
                autoPlay = setInterval(nextSlide, 5000);
            });
            
            // Inicializar el carrusel
            updateCarousel();
    })

    document.getElementById('userForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Obtener valores del formulario
            const firstName = document.getElementById('firstName').value;
            const lastName = document.getElementById('lastName').value;
            const birthDate = document.getElementById('birthDate').value;
            const comments = document.getElementById('comments').value;
            
            // Validación básica
            if (!firstName || !lastName || !birthDate) {
                alert('Por favor, complete todos los campos obligatorios.');
                return;
            }
            
            // Mostrar mensaje de éxito
            alert(`¡Registro exitoso!\n\nNombre: ${firstName} ${lastName}\nFecha de Nacimiento: ${birthDate}\nComentarios: ${comments || 'Ninguno'}`);
            
            // Limpiar formulario
            document.getElementById('userForm').reset();
        });