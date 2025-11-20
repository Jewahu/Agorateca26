<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        header("Location: login.php?error=empty");
        exit();
    }

    $stmt = $conn->prepare("SELECT id, username, email, password, is_verified FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $conn->close();

    if ($result && $result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            // Verificar si la cuenta está activada
            if ($user['is_verified'] == 1) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                
                // Redirigir al nuevo index.php
                header("Location: index.php");
                exit();
            } else {
                // Cuenta no verificada
                header("Location: login.php?error=not_verified");
                exit();
            }
        } else {
            // Contraseña incorrecta
            header("Location: login.php?error=invalid");
            exit();
        }
    } else {
        // Usuario no encontrado
        header("Location: login.php?error=notfound");
        exit();
    }
}
?>