<?php
session_start(); // Asegúrate de iniciar la sesión
require_once './app/config/conexion.php'; // Asegúrate de que esto esté correcto

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];

    try {
        // Consulta a la base de datos usando PDO
        $query = "SELECT * FROM t_alumnos WHERE nombre = :nombre AND apellido = :apellido";
        $stmt = $conexion->prepare($query);

        // Enlaza los parámetros usando PDO
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);

        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $alumno = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Guardar el ID del alumno en la sesión
            $_SESSION['alumno_id'] = $alumno['id_alumnos'];

            // Enviar respuesta al cliente
            echo json_encode(['success' => true, 'message' => 'Inicio de sesión correctamente']);
            exit();
        } else {
            echo json_encode(['success' => false, 'message' => 'Credenciales incorrectas.']);
            exit();
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => "Error en la consulta: " . $e->getMessage()]);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión Alumno</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./public/css/style.css">
</head>
<body>
    <div class="login-container text-center">
        <i class="fas fa-user-graduate"></i> <!-- Ícono de estudiante -->
        <h3>Iniciar Sesión Alumno</h3>
        <form id="loginForm" method="POST"> <!-- Cambiar a "" para enviar al mismo script -->
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-0">
                        <i class="fas fa-user" style="color: white;"></i>
                    </span>
                    <input type="text" class="form-control rounded-pill border-white" id="nombre" name="nombre" required style="border-left: none; color: white; background-color: transparent;">
                </div>
            </div>

            <div class="mb-3">
                <label for="apellido" class="form-label">Apellido</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-0">
                        <i class="fas fa-user" style="color: white;"></i>
                    </span>
                    <input type="text" class="form-control rounded-pill border-white" id="apellido" name="apellido" required style="border-left: none; color: white; background-color: transparent;">
                </div>
            </div>

            <div class="d-grid">
                <button type="submit" id="btn_iniciar" class="btn login-btn">
                    <span>Iniciar Sesión</span>
                </button>
            </div>

            <div id="error-message" class="alert alert-danger mt-3" style="display: none;"></div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="./public/js/login_alumnos"></script>
    <script src="./public/js/alumnos.js"></script>
    
    <script>
        document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevenir el envío del formulario
    const formData = new FormData(this);

    fetch('', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: data.message,
            }).then(() => {
                window.location.href = 'vistadatos.php'; // Redirigir a la vista de datos
            });
        } else {
            document.getElementById('error-message').textContent = data.message;
            document.getElementById('error-message').style.display = 'block';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('error-message').textContent = 'Error en la solicitud.';
        document.getElementById('error-message').style.display = 'block';
    });
});
    </script>
</body>
</html>
