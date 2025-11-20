<?php
require_once 'config.php';
require_once 'email_config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$message = '';
$success = false;

if (isset($_GET['email'])) {
    $email = trim($_GET['email']);
    
    // Verificar si el usuario existe y no está verificado
    $stmt = $conn->prepare("SELECT id, username, is_verified, verification_token FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        if ($user['is_verified'] == 1) {
            $message = 'Esta cuenta ya ha sido verificada. Puedes iniciar sesión.';
        } else {
            // Generar nuevo token si no existe
            $verification_token = $user['verification_token'];
            if (empty($verification_token)) {
                $verification_token = bin2hex(random_bytes(32));
                $stmt_update = $conn->prepare("UPDATE users SET verification_token = ? WHERE id = ?");
                $stmt_update->bind_param("si", $verification_token, $user['id']);
                $stmt_update->execute();
                $stmt_update->close();
            }
            
            // Enviar correo
            $verification_link = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/verify.php?token=" . $verification_token;
            $username = $user['username'];
            
            $mail = new PHPMailer(true);
            
            try {
                $mail->isSMTP();
                $mail->Host       = SMTP_HOST;
                $mail->SMTPAuth   = true;
                $mail->Username   = SMTP_USERNAME;
                $mail->Password   = SMTP_PASSWORD;
                $mail->SMTPSecure = SMTP_SECURE;
                $mail->Port       = SMTP_PORT;
                $mail->CharSet    = 'UTF-8';
                
                $mail->setFrom(MAIL_FROM_EMAIL, MAIL_FROM_NAME);
                $mail->addAddress($email, $username);
                
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
                            <h2>Verificación de cuenta</h2>
                            <p>Hola, $username</p>
                            <p>Has solicitado un nuevo correo de verificación.</p>
                            <p>Para activar tu cuenta, haz clic en el siguiente botón:</p>
                            <center>
                                <a href='$verification_link' class='button'>Activar mi cuenta</a>
                            </center>
                            <p>O copia y pega este enlace en tu navegador:</p>
                            <p style='word-break: break-all; color: #666;'>$verification_link</p>
                            <p><strong>Este enlace expirará en 24 horas.</strong></p>
                        </div>
                        <div class='footer'>
                            <p>&copy; 2025 Ágorateca Escolar</p>
                        </div>
                    </div>
                </body>
                </html>
                ";
                
                $mail->AltBody = "Verificación de cuenta\n\nHola, $username\n\nPara activar tu cuenta, copia y pega este enlace:\n\n$verification_link";
                
                $mail->send();
                $success = true;
                $message = '¡Correo reenviado! Revisa tu bandeja de entrada.';
                
            } catch (Exception $e) {
                $message = 'Error al enviar el correo. Intenta de nuevo más tarde.';
            }
        }
    } else {
        $message = 'No se encontró una cuenta con ese correo electrónico.';
    }
    
    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reenviar Verificación - Ágorateca Escolar</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Roboto:wght@400&display=swap" rel="stylesheet">
    <style>
        .resend-container {
            text-align: center;
            padding: 40px;
            max-width: 500px;
            margin: 0 auto;
        }
        .message-box {
            padding: 20px;
            border-radius: 10px;
            margin: 30px 0;
        }
        .success-box {
            background-color: #e8f5e9;
            border: 2px solid #4CAF50;
            color: #2e7d32;
        }
        .error-box {
            background-color: #ffebee;
            border: 2px solid #f44336;
            color: #c62828;
        }
        .action-btn {
            display: inline-block;
            padding: 15px 40px;
            background-color: #a92a2a;
            color: white;
            text-decoration: none;
            border-radius: 25px;
            margin-top: 20px;
        }
        .action-btn:hover {
            background-color: #8c2323;
        }
    </style>
</head>
<body>
    <div class="resend-container">
        <h1>ÁGORATECA ESCOLAR</h1>
        <h2 style="color: #601a1a;">Reenviar Verificación</h2>
        
        <?php if (!empty($message)): ?>
            <div class="message-box <?php echo $success ? 'success-box' : 'error-box'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <?php if (!isset($_GET['email'])): ?>
            <form method="GET" action="">
                <div class="input-group">
                    <input type="email" name="email" placeholder="Tu correo electrónico" required>
                </div>
                <button type="submit" class="submit-btn">Reenviar correo</button>
            </form>
        <?php else: ?>
            <a href="login.php" class="action-btn">Volver al Login</a>
        <?php endif; ?>
    </div>
</body>
</html>
