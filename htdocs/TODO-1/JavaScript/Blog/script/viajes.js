// Mostrar el formulario al hacer clic en "Añadir Entrada"
document.getElementById('añadir-entrada').addEventListener('click', function(event) {
    event.preventDefault(); // Evitar que el enlace recargue la página
    document.getElementById('formulario-agregar').style.display = 'block'; // Mostrar el formulario
});

// Mostrar las entradas guardadas del localStorage
function mostrarEntradas() {
    const entradas = JSON.parse(localStorage.getItem('entradas')) || [];
    const contenedor = document.getElementById('entradas');
    contenedor.innerHTML = '';  // Limpiar contenido actual

    entradas.forEach(entrada => {
        const div = document.createElement('div');
        div.classList.add('entry'); 
        div.innerHTML = `
            <h3>${entrada.titulo}</h3>
            <img src="${entrada.foto}" alt="${entrada.titulo}">
            <p>${entrada.descripcion}</p>
            <p><strong>Ubicación: </strong>${entrada.ubicacion}</p>
        `;
        contenedor.appendChild(div);
    });
}

// Función para agregar nueva entrada al localStorage
document.getElementById('form-nueva-entrada').addEventListener('submit', function(event) {
    event.preventDefault();

    const titulo = document.getElementById('titulo').value; 
    const descripcion = document.getElementById('descripcion').value; 
    const ubicacion = document.getElementById('ubicacion').value;
    const foto = document.getElementById('foto').value;

    const nuevaEntrada = {
        titulo, 
        descripcion, 
        ubicacion,
        foto
    };

    // Obtener las entradas existentes del localStorage, si las hay
    let entradas = JSON.parse(localStorage.getItem('entradas')) || [];
    entradas.push(nuevaEntrada);
    localStorage.setItem('entradas', JSON.stringify(entradas));

    // Limpiar el formulario
    document.getElementById('form-nueva-entrada').reset();

    // Ocultar el formulario nuevamente
    document.getElementById('formulario-agregar').style.display = 'none';

    // Volver a mostrar las entradas
    mostrarEntradas();
});

// Mostrar las entradas cuando la página cargue
document.addEventListener('DOMContentLoaded', mostrarEntradas);
