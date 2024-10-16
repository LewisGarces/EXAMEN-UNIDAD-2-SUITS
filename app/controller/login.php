<?php
// Inicia la sesión para poder manejar variables de sesión
session_start();

// Requiere el archivo de conexión a la base de datos
require_once '../config/conexion.php'; // Asegúrate de que la ruta sea correcta

// Verifica que los datos hayan sido enviados a través de POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtiene los valores del formulario
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    // Prepara la consulta para verificar las credenciales
    $query = "SELECT id_usuario, usuario, password FROM t_usuario WHERE usuario = :usuario AND password = :password";
    $stmt = $conexion->prepare($query);
    $stmt->bindParam(':usuario', $usuario);
    $stmt->bindParam(':password', $password);
    
    // Ejecuta la consulta
    $stmt->execute();

    // Verifica si se encontró un usuario
    if ($stmt->rowCount() > 0) {
        $usuario_encontrado = $stmt->fetch(PDO::FETCH_ASSOC);

        // Almacena el **ID** del usuario en la sesión
        $_SESSION['usuario'] = $usuario_encontrado['id_usuario']; 

        // Devuelve una respuesta exitosa
        echo json_encode(['success' => true, 'message' => 'Iniciaste sesión correctamente.']);
    } else {
        // Devuelve una respuesta de error si las credenciales son incorrectas
        echo json_encode(['success' => false, 'message' => 'Credenciales incorrectas.']);
    }
} else {
    // Devuelve un error si no es una solicitud POST
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>
