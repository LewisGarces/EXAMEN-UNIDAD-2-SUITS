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