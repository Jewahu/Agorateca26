<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión - Ágorateca Escolar</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Roboto:wght@400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="form-container">
        <h1>ÁGORATECA ESCOLAR</h1>
        <h2>Inicio de sesión</h2>
        <?php
        if (isset($_GET['error'])) {
            $error_message = '';
            switch ($_GET['error']) {
                case 'empty':
                    $error_message = 'Por favor, completa todos los campos.';
                    break;
                case 'invalid':
                    $error_message = 'Contraseña o email incorrectos.';
                    break;
                case 'notfound':
                    $error_message = 'Usuario no encontrado.';
                    break;
                case 'not_verified':
                    $error_message = 'Tu cuenta no ha sido verificada. Por favor, revisa tu correo electrónico.';
                    break;
            }
            if ($error_message) {
                echo '<div class="error-message">' . $error_message . '</div>';
            }
        }
        if (isset($_GET['verified'])) {
            echo '<div class="success-message">¡Tu cuenta ha sido verificada! Ya puedes iniciar sesión.</div>';
        }
        ?>
        <form action="login_process.php" method="POST">
            <div class="input-group">
                <input type="email" id="email" name="email" placeholder="Correo electrónico" required>
            </div>
            <div class="input-group password-wrapper">
                <input type="password" id="password" name="password" placeholder="Contraseña" required>
                 <i class="fas fa-eye-slash toggle-password" data-target="password"></i>
            </div>
            <div class="options">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Recordar contraseña</label>
            </div>
            <button type="submit" class="submit-btn">Iniciar sesión</button>
        </form>
        <div class="footer-link">
            <p>¿Aún no tienes una cuenta? <a href="register.php">Regístrate aquí</a></p>
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