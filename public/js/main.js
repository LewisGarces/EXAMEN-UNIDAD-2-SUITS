// Cargar alumnos al inicio
const cargarAlumnos = () => {
    fetch("app/controller/consulta.php")
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const tbody = document.querySelector('tbody');
                tbody.innerHTML = ''; 
                data.data.forEach(alumno => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${alumno.id_alumnos}</td>
                        <td>${alumno.nombre}</td>
                        <td>${alumno.apellido}</td>
                        <td>${alumno.año_ingreso}</td>
                        <td>${alumno.carrera}</td>
                        <td>${alumno.fecha_nacimiento}</td>
                        <td>
                            <button class="btn btn-warning" onclick="abrirModalActualizar(${alumno.id_alumnos}, '${alumno.nombre}', '${alumno.apellido}', ${alumno.año_ingreso}, '${alumno.carrera}', '${alumno.fecha_nacimiento}')">Actualizar</button>
                            <button class="btn btn-danger" onclick="eliminarAlumno(${alumno.id_alumnos})">Eliminar</button>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });
            } else {
                swal("Error", data.message, "error");
            }
        })
        .catch(error => {
            swal("Error", "No se pudo cargar los alumnos.", "error");
        });
};

// Eliminar alumno
const eliminarAlumno = (id) => {
    swal({
        title: "¿Estás seguro?",
        text: "No podrás recuperar este alumno una vez eliminado.",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
    .then((willDelete) => {
        if (willDelete) {
            fetch("app/controller/eliminar.php", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id_alumnos: id }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    swal("Éxito", data.message, "success");
                    cargarAlumnos();
                } else {
                    swal("Error", data.message, "error");
                }
            })
            .catch(error => {
                swal("Error", "No se pudo eliminar el alumno.", "error");
            });
        }
    });
};

// Función para abrir el modal de actualización
const abrirModalActualizar = (id, nombre, apellido, anio_ingreso, carrera, fecha_nacimiento) => {
    document.getElementById('id_alumno').value = id;
    document.getElementById('nombre_alumno_modal').value = nombre;
    document.getElementById('apellido_alumno_modal').value = apellido;
    document.getElementById('anio_ingreso_modal').value = anio_ingreso;
    document.getElementById('carrera_modal').value = carrera;
    document.getElementById('fecha_nacimiento_modal').value = fecha_nacimiento;
    $('#modalActualizar').modal('show');
};

// Agregar alumno
document.getElementById('formAgregarAlumno').addEventListener('submit', (event) => {
    event.preventDefault();
    const formData = new FormData(event.target);

    fetch("app/controller/insertar.php", {
        method: 'POST',
        body: formData,
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            swal("Éxito", data.message, "success");
            cargarAlumnos();
            document.getElementById('formAgregarAlumno').reset();
        } else {
            swal("Error", data.message, "error");
        }
    })
    .catch(error => {
        swal("Error", "No se pudo agregar el alumno.", "error");
    });
});

// Actualizar alumno
document.getElementById('formActualizarAlumno').addEventListener('submit', (event) => {
    event.preventDefault();
    const formData = new FormData(event.target);
    formData.append('accion', 'actualizar');

    fetch("app/controller/actualizar.php", {
        method: 'POST',
        body: formData,
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            swal("Éxito", data.message, "success");
            cargarAlumnos();
            $('#modalActualizar').modal('hide');
        } else {
            swal("Error", data.message, "error");
        }
    })
    .catch(error => {
        swal("Error", "No se pudo actualizar el alumno.", "error");
    });
});

// Cerrar sesión
document.getElementById('cerrarSesionBtn').addEventListener('click', () => {
    // Confirmación para cerrar la sesión
    swal({
        title: "¿Estás seguro?",
        text: "Se cerrará tu sesión actual.",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((willLogout) => {
        if (willLogout) {
            // Enviar petición para cerrar la sesión
            fetch('app/controller/cerrar_sesion.php', {
                method: 'POST',
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mostrar mensaje de éxito y redirigir al login
                    swal("Éxito", data.message, "success")
                    .then(() => {
                        window.location.href = 'login.php';  // Redirige al login
                    });
                } else {
                    swal("Error", "No se pudo cerrar la sesión.", "error");
                }
            })
            .catch(error => {
                swal("Error", "Hubo un problema al cerrar la sesión.", "error");
            });
        }
    });
});

// Cargar alumnos al iniciar la página
window.onload = cargarAlumnos;
