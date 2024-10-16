<?php
session_start();
require_once './app/config/conexion.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['alumno_id'])) {
    echo json_encode(['success' => false, 'message' => 'No se ha iniciado sesión.']);
    exit;
}

// Obtener los datos del alumno
$id_alumno = $_SESSION['alumno_id'];
$query = "SELECT * FROM t_alumnos WHERE id_alumnos = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $id_alumno);
$stmt->execute();
$result = $stmt->get_result();

if ($alumno = $result->fetch_assoc()) {
    echo json_encode(['success' => true, 'data' => $alumno]);
} else {
    echo json_encode(['success' => false, 'message' => 'No se encontraron datos.']);
}
?>
