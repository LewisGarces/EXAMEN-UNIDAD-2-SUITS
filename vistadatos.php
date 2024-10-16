<?php
session_start(); // Iniciar la sesión
require_once './app/config/conexion.php'; // Asegúrate de que esto esté correcto

// Verificar si el ID del alumno está presente en la sesión
if (!isset($_SESSION['alumno_id'])) {
    header("Location: login_alumnos.php"); // Redirigir si no hay sesión activa
    exit();
}

// Obtener el ID del alumno desde la sesión
$id_alumno = $_SESSION['alumno_id'];

try {
    // Consulta a la base de datos usando PDO
    $query = "SELECT * FROM t_alumnos WHERE id_alumnos = :id_alumno";
    $stmt = $conexion->prepare($query);

    // Enlazar el parámetro usando PDO
    $stmt->bindParam(':id_alumno', $id_alumno, PDO::PARAM_INT);
    
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $alumno = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        $error = "No se encontraron datos del alumno.";
    }
} catch (PDOException $e) {
    $error = "Error en la consulta: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vista de Datos del Alumno</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container mt-5">
        <h3>Datos del Alumno</h3>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php else: ?>
            <table class="table">
                <tr>
                    <th>ID Alumno</th>
                    <td><?= htmlspecialchars($alumno['id_alumnos']) ?></td>
                </tr>
                <tr>
                    <th>Nombre</th>
                    <td><?= htmlspecialchars($alumno['nombre']) ?></td>
                </tr>
                <tr>
                    <th>Apellido</th>
                    <td><?= htmlspecialchars($alumno['apellido']) ?></td>
                </tr>
                <tr>
                    <th>Año de Ingreso</th>
                    <td><?= htmlspecialchars($alumno['año_ingreso']) ?></td>
                </tr>
                <tr>
                    <th>Carrera</th>
                    <td><?= htmlspecialchars($alumno['carrera']) ?></td>
                </tr>
                <tr>
                    <th>Fecha de Nacimiento</th>
                    <td><?= htmlspecialchars($alumno['fecha_nacimiento']) ?></td>
                </tr>
            </table>
            <button id="btn_logout" class="btn btn-danger">Cerrar Sesión</button>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.getElementById('btn_logout').addEventListener('click', function() {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡Quieres cerrar sesión!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, cerrar sesión',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Cerrar sesión
                    fetch('./app/controller/logout.php', {
                        method: 'POST',
                        credentials: 'include' // Incluye cookies de sesión
                    })
                    .then(response => {
                        if (response.ok) {
                            // Mostrar mensaje de éxito y redirigir
                            Swal.fire('Éxito', 'Sesión cerrada exitosamente.', 'success').then(() => {
                                window.location.href = 'login.php'; // Redirigir a login.php
                            });
                        } else {
                            return response.json().then(data => {
                                Swal.fire('Error', data.message || 'Ocurrió un error al cerrar sesión.', 'error');
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error en el cierre de sesión:', error);
                        Swal.fire('Error', 'Ocurrió un error al cerrar sesión.', 'error');
                    });
                }
            });
        });
    </script>
</body>
</html>

