// Función para mostrar alertas con SweetAlert
const mostrarAlerta = (tipo, mensaje) => {
    swal(tipo === 'success' ? "Éxito" : "Error", mensaje, tipo);
};

// Cargar información del alumno
const cargarDatosAlumno = () => {
    fetch("app/controller/consultar.php") // Cambiar a la URL correcta de tu controlador
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Asignar los datos a los elementos del DOM, si es necesario
                document.getElementById('id_alumno').textContent = data.data.id_alumnos;
                document.getElementById('nombre').textContent = data.data.nombre;
                document.getElementById('apellido').textContent = data.data.apellido;
                document.getElementById('anio_ingreso').textContent = data.data.año_ingreso;
                document.getElementById('carrera').textContent = data.data.carrera;
                document.getElementById('fecha_nacimiento').textContent = data.data.fecha_nacimiento;
            } else {
                mostrarAlerta('error', data.message);
            }
        })
        .catch(error => {
            mostrarAlerta('error', "No se pudo cargar los datos del alumno.");
        });
};

// Cargar datos al iniciar la página
window.onload = cargarDatosAlumno;


