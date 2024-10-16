<?php
require_once './app/config/conexion.php'; // Verifica que esta ruta sea la correcta

session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

$query = "SELECT * FROM t_alumnos";
$result = $conexion->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Alumnos</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" href="./public/css/main.css">
</head>
<body>
    <div class="container mt-2">
        <div class="d-flex justify-content-between align-items-center">
            <h5>Bienvenido docente</h5>
            <div>
                <button class="btn btn-danger" id="cerrarSesionBtn">
                    Cerrar sesión <i class="fas fa-sign-out-alt"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="container mt-5">
        <h2>Agregar Alumno</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="card mb-2">
                    <div class="card-body bg-light text-dark">
                        <form id="formAgregarAlumno">
                            <div class="form-group">
                                <label for="nombre_alumno">Nombre:</label>
                                <input type="text" class="form-control" id="nombre_alumno" name="nombre_alumno" required>
                            </div>
                            <div class="form-group">
                                <label for="apellido_alumno">Apellido:</label>
                                <input type="text" class="form-control" id="apellido_alumno" name="apellido_alumno" required>
                            </div>
                            <div class="form-group">
                                <label for="anio_ingreso">Año de Ingreso:</label>
                                <input type="number" class="form-control" id="anio_ingreso" name="anio_ingreso" required>
                            </div>
                            <div class="form-group">
                                <label for="carrera">Carrera:</label>
                                <input type="text" class="form-control" id="carrera" name="carrera" required>
                            </div>
                            <div class="form-group">
                                <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
                                <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required>
                            </div>
                            <button type="submit" class="btn btn-success">Agregar <i class="fas fa-plus"></i></button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <h3 class="mt-8">Lista de Alumnos</h3>
                <table class="table table-warning table-striped-columns">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Año de Ingreso</th>
                            <th>Carrera</th>
                            <th>Fecha de Nacimiento</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)) { ?>
                            <tr>
                                <td><?php echo $row['id_alumnos']; ?></td>
                                <td><?php echo $row['nombre']; ?></td>
                                <td><?php echo $row['apellido']; ?></td>
                                <td><?php echo $row['año_ingreso']; ?></td>
                                <td><?php echo $row['carrera']; ?></td>
                                <td><?php echo $row['fecha_nacimiento']; ?></td>
                                <td>
                                    <button class="btn btn-warning" onclick="abrirModalActualizar(<?php echo $row['id_alumnos']; ?>, '<?php echo $row['nombre']; ?>', '<?php echo $row['apellido']; ?>', <?php echo $row['año_ingreso']; ?>, '<?php echo $row['carrera']; ?>', '<?php echo $row['fecha_nacimiento']; ?>')">
                                        <i class="fas fa-edit"></i> Actualizar
                                    </button>
                                    <button class="btn btn-danger" onclick="eliminarAlumno(<?php echo $row['id_alumnos']; ?>)">
                                        <i class="fas fa-trash-alt"></i> Eliminar
                                    </button>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal de Actualización de Alumno -->
    <div class="modal fade" id="modalActualizar" tabindex="-1" aria-labelledby="modalActualizarLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalActualizarLabel">Actualizar Alumno</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formActualizarAlumno">
                        <input type="hidden" id="id_alumno" name="id_alumno">
                        <div class="form-group">
                            <label for="nombre_alumno_modal">Nombre:</label>
                            <input type="text" class="form-control" id="nombre_alumno_modal" name="nombre_alumno_modal" required>
                        </div>
                        <div class="form-group">
                            <label for="apellido_alumno_modal">Apellido:</label>
                            <input type="text" class="form-control" id="apellido_alumno_modal" name="apellido_alumno_modal" required>
                        </div>
                        <div class="form-group">
                            <label for="anio_ingreso_modal">Año de Ingreso:</label>
                            <input type="number" class="form-control" id="anio_ingreso_modal" name="anio_ingreso_modal" required>
                        </div>
                        <div class="form-group">
                            <label for="carrera_modal">Carrera:</label>
                            <input type="text" class="form-control" id="carrera_modal" name="carrera_modal" required>
                        </div>
                        <div class="form-group">
                            <label for="fecha_nacimiento_modal">Fecha de Nacimiento:</label>
                            <input type="date" class="form-control" id="fecha_nacimiento_modal" name="fecha_nacimiento_modal" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Actualizar Alumno <i class="fas fa-save"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="./public/js/main.js"></script>
</body>
</html>
