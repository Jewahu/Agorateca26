<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse - Ágorateca Escolar</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Roboto:wght@400&display=swap" rel="stylesheet">
    <!-- Iconos de Font Awesome para el ojo -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="form-container">
        <h1>ÁGORATECA ESCOLAR</h1>
        <h2>Registrarse</h2>
        <?php
        // Mostrar mensajes de error
        if (isset($_GET['error'])) {
            $error_message = '';
            switch ($_GET['error']) {
                case 'empty':
                    $error_message = 'Por favor, completa todos los campos.';
                    break;
                case 'password_mismatch':
                    $error_message = 'Las contraseñas no coinciden.';
                    break;
                case 'password_short':
                    $error_message = 'La contraseña debe tener al menos 6 caracteres.';
                    break;
                case 'email_invalid_domain':
                    $error_message = 'Solo se permiten correos con el dominio @cetis26.edu.mx';
                    break;
                case 'email_exists':
                    $error_message = 'Este correo ya está registrado.';
                    break;
                case 'username_exists':
                    $error_message = 'Este nombre de usuario ya existe.';
                    break;
                case 'database':
                case 'email_send_failed':
                    $error_message = 'Error al crear la cuenta. Intenta de nuevo.';
                    break;
            }
            if ($error_message) {
                echo '<div class="error-message">' . $error_message . '</div>';
            }
        }
        ?>
        <form action="register_process.php" method="POST">
            <div class="input-group">
                <input type="email" id="email" name="email" placeholder="Correo electrónico" required>
            </div>
            <div class="input-group">
                <input type="text" id="username" name="username" placeholder="Nombre de usuario" required>
            </div>
            <div class="input-group password-wrapper">
                <input type="password" id="password" name="password" placeholder="Contraseña" required>
                <i class="fas fa-eye-slash toggle-password" data-target="password"></i>
            </div>
            <div class="input-group password-wrapper">
                <input type="password" id="verify_password" name="verify_password" placeholder="Verificar contraseña" required>
                <i class="fas fa-eye-slash toggle-password" data-target="verify_password"></i>
            </div>
            <button type="submit" class="submit-btn">Registrarse</button>
        </form>
        <div class="footer-link">
            <p>¿Ya tienes una cuenta? <a href="login.php">Iniciar sesión</a></p>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const togglePasswordIcons = document.querySelectorAll('.toggle-password');
        togglePasswordIcons.forEach(icon => {
            icon.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const passwordInput = document.getElementById(targetId);
                
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    this.classList.remove('fa-eye-slash');
                    this.classList.add('fa-eye');
                } else {
                    passwordInput.type = 'password';
                    this.classList.remove('fa-eye');
                    this.classList.add('fa-eye-slash');
                }
            });
        });
    });
    </script>
</body>
</html>