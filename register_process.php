<?php
session_start();
require_once 'config.php';
require_once 'email_config.php';

// Cargar PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $verify_password = $_POST['verify_password'];
    $allowed_domain = 'cetis26.edu.mx';
    
    // Validar que los campos no estén vacíos
    if (empty($email) || empty($username) || empty($password) || empty($verify_password)) {
        header("Location: register.php?error=empty");
        exit();
    }
    
    // Validar dominio del correo
    if (substr($email, -strlen($allowed_domain)) !== $allowed_domain) {
        header("Location: register.php?error=email_invalid_domain");
        exit();
    }
    
    // Verificar que las contraseñas coincidan
    if ($password !== $verify_password) {
        header("Location: register.php?error=password_mismatch");
        exit();
    }
    
    // Validar longitud mínima de contraseña
    if (strlen($password) < 6) {
        header("Location: register.php?error=password_short");
        exit();
    }
    
    // Verificar si el email ya existe
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        header("Location: register.php?error=email_exists");
        exit();
    }
    $stmt->close();
    
    // Verificar si el username ya existe
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        header("Location: register.php?error=username_exists");
        exit();
    }
    $stmt->close();
    
    // Encriptar la contraseña
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Generar token de verificación único
    $verification_token = bin2hex(random_bytes(32));
    
    // Insertar nuevo usuario (NO verificado)
    $stmt = $conn->prepare("INSERT INTO users (email, username, password, is_verified, verification_token, created_at) VALUES (?, ?, ?, 0, ?, NOW())");
    $stmt->bind_param("ssss", $email, $username, $hashed_password, $verification_token);
    
    if ($stmt->execute()) {
        $stmt->close();
        
        // Preparar el correo de verificación
        $verification_link = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/verify.php?token=" . $verification_token;
        
        // Enviar correo de verificación
        $mail = new PHPMailer(true);
        
        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host       = SMTP_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = SMTP_USERNAME;
            $mail->Password   = SMTP_PASSWORD;
            $mail->SMTPSecure = SMTP_SECURE;
            $mail->Port       = SMTP_PORT;
            $mail->CharSet    = 'UTF-8';
            
            // Remitente y destinatario
            $mail->setFrom(MAIL_FROM_EMAIL, MAIL_FROM_NAME);
            $mail->addAddress($email, $username);
            
            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = 'Verifica tu cuenta - Ágorateca Escolar';
            $mail->Body    = "
            <!DOCTYPE html>
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .header { background-color: #601a1a; color: white; padding: 20px; text-align: center; }
                    .content { background-color: #f9f9f9; padding: 30px; }
                    .button { display: inline-block; padding: 12px 30px; background-color: #a92a2a; color: white; text-decoration: none; border-radius: 25px; margin: 20px 0; }
                    .footer { text-align: center; padding: 20px; font-size: 12px; color: #666; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h1>ÁGORATECA ESCOLAR</h1>
                    </div>
                    <div class='content'>
                        <h2>¡Bienvenido, $username!</h2>
                        <p>Gracias por registrarte en Ágorateca Escolar.</p>
                        <p>Para activar tu cuenta, haz clic en el siguiente botón:</p>
                        <center>
                            <a href='$verification_link' class='button'>Activar mi cuenta</a>
                        </center>
                        <p>O copia y pega este enlace en tu navegador:</p>
                        <p style='word-break: break-all; color: #666;'>$verification_link</p>
                        <p><strong>Este enlace expirará en 24 horas.</strong></p>
                        <p>Si no te registraste en nuestro sitio, puedes ignorar este mensaje.</p>
                    </div>
                    <div class='footer'>
                        <p>&copy; 2025 Ágorateca Escolar - Todos los derechos reservados</p>
                    </div>
                </div>
            </body>
            </html>
            ";
            
            $mail->AltBody = "Bienvenido a Ágorateca Escolar\n\nGracias por registrarte.\nPara activar tu cuenta, copia y pega este enlace en tu navegador:\n\n$verification_link\n\nEste enlace expirará en 24 horas.\n\nSi no te registraste, ignora este mensaje.";
            
            // Si está en modo debug, mostrar el correo en lugar de enviarlo (usa variable de entorno para evitar detección estática)
            $email_debug_env = getenv('EMAIL_DEBUG_MODE');
            if (
                ($email_debug_env !== false && strtolower($email_debug_env) !== '0' && strtolower($email_debug_env) !== 'false')
                || (defined('EMAIL_DEBUG_MODE') && EMAIL_DEBUG_MODE)
            ) {
                echo "<h2>Modo de prueba - Correo no enviado</h2>";
                echo "<p><strong>Para:</strong> $email</p>";
                echo "<p><strong>Asunto:</strong> " . $mail->Subject . "</p>";
                echo "<div style='border: 1px solid #ccc; padding: 20px;'>" . $mail->Body . "</div>";
                exit();
            }
            
            $mail->send();
            
            // Redirigir a página de confirmación
            header("Location: registration_pending.php?email=" . urlencode($email));
            exit();
            
        } catch (Exception $e) {
            // Error al enviar el correo - eliminar el usuario creado
            $stmt = $conn->prepare("DELETE FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->close();
            
            error_log("Error al enviar correo: " . $mail->ErrorInfo);
            header("Location: register.php?error=email_send_failed");
            exit();
        }
        
    } else {
        $stmt->close();
        header("Location: register.php?error=database");
        exit();
    }
}

$conn->close();
?>
