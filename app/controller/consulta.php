<?php
require_once '../config/conexion.php';

$stmt = $conexion->prepare("SELECT * FROM t_alumnos");
if ($stmt->execute()) {
    $alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['success' => true, 'data' => $alumnos]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al consultar los alumnos.']);
}
?>
