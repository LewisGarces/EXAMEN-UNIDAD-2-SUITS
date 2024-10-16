<?php
require_once '../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'actualizar') {
    $id = $_POST['id_alumno'];
    $nombre = $_POST['nombre_alumno_modal'];
    $apellido = $_POST['apellido_alumno_modal'];
    $anio_ingreso = $_POST['anio_ingreso_modal'];
    $carrera = $_POST['carrera_modal'];
    $fecha_nacimiento = $_POST['fecha_nacimiento_modal'];

    $stmt = $conexion->prepare("UPDATE t_alumnos SET nombre = ?, apellido = ?, año_ingreso = ?, carrera = ?, fecha_nacimiento = ? WHERE id_alumnos = ?");
    if ($stmt->execute([$nombre, $apellido, $anio_ingreso, $carrera, $fecha_nacimiento, $id])) {
        echo json_encode(['success' => true, 'message' => 'Alumno actualizado correctamente.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar el alumno.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}
?>
