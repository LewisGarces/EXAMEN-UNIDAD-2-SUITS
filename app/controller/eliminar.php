<?php
require_once '../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['id_alumnos'])) {
        $id = $data['id_alumnos'];
        $stmt = $conexion->prepare("DELETE FROM t_alumnos WHERE id_alumnos = ?");
        if ($stmt->execute([$id])) {
            echo json_encode(['success' => true, 'message' => 'Alumno eliminado correctamente.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar el alumno.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'ID del alumno no proporcionado.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}
?>
