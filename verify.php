<?php
require_once 'config.php';

$message = "Token inválido o expirado.";
$message_type = "error";

if (isset($_GET['token']) && !empty($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $conn->prepare("SELECT id, is_verified FROM users WHERE verification_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        if ($user['is_verified'] == 0) {
            // Marcar usuario como verificado y limpiar el token
            $update_stmt = $conn->prepare("UPDATE users SET is_verified = 1, verification_token = NULL WHERE id = ?");
            $update_stmt->bind_param("i", $user['id']);
            
            if ($update_stmt->execute()) {
                // Redirigir al login con mensaje de éxito
                header("Location: login.php?verified=true");
                exit();
            } else {
                $message = "Error al verificar la cuenta. Inténtalo de nuevo.";
            }
            $update_stmt->close();
        } else {
            // El usuario ya estaba verificado, redirigir al login
             header("Location: login.php?verified=true");
             exit();
        }
    }
    $stmt->close();
}

// Si llega aquí, es porque hubo un error
header("Location: login.php?error=token_invalid");
exit();
?>