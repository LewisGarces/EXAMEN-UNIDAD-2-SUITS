<?php
require_once '../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre_alumno'];
    $apellido = $_POST['apellido_alumno'];
    $anio_ingreso = $_POST['anio_ingreso'];
    $carrera = $_POST['carrera'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];

    $stmt = $conexion->prepare("INSERT INTO t_alumnos (nombre, apellido, año_ingreso, carrera, fecha_nacimiento) VALUES (?, ?, ?, ?, ?)");
    if ($stmt->execute([$nombre, $apellido, $anio_ingreso, $carrera, $fecha_nacimiento])) {
        echo json_encode(['success' => true, 'message' => 'Alumno agregado correctamente.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al agregar el alumno.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}
?>
