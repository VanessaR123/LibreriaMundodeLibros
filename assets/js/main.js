// Archivo: assets/js/main.js

// Inicializar el carrusel
function initCarousel() {
    let index = 0;
    const items = document.querySelectorAll('.carousel-item');
    const descriptions = document.querySelectorAll('.description');
    showItem(index);

    // Establecer intervalo para deslizamiento automático
    setInterval(() => {
        hideItem(index);
        index = (index + 1) % items.length;
        showItem(index);
    }, 3000);

    items.forEach((item, i) => {
        
        const img = item.querySelector('img');

        // Ampliar la imagen cuando el mouse esté sobre ella.
        img.onmouseover = function () {
            img.style.transform = 'scale(2)';
        };
        
        // Restaura el tamaño de la imagen cuando el mouse está fuera
        img.onmouseout = function () {
            img.style.transform = 'scale(1)';
        };

        // Muestra el texto de descripción cuando se hace clic en la imagen.
        img.onclick = function () {
            descriptions[i].style.display = 'block';
        };
    });
}

// Muestra el elemento del carrusel en el índice dado
function showItem(index) {
    const items = document.querySelectorAll('.carousel-item');
    items[index].style.display = 'block';
}

// Ocultar el elemento del carrusel en el índice dado
function hideItem(index) {
    const items = document.querySelectorAll('.carousel-item');
    items[index].style.display = 'none';
}