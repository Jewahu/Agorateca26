<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Ágorateca Escolar</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Roboto:wght@400&display=swap" rel="stylesheet">
    <style>
        .dashboard-container {
            text-align: center;
            padding: 40px;
        }
        .welcome-message {
            color: #601a1a;
            margin-bottom: 30px;
        }
        .logout-btn {
            padding: 10px 30px;
            background-color: #a92a2a;
            color: white;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 1em;
        }
        .logout-btn:hover {
            background-color: #8c2323;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1>ÁGORATECA ESCOLAR</h1>
        <div class="welcome-message">
            <h2>¡Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
            <p>Email: <?php echo htmlspecialchars($_SESSION['email']); ?></p>
        </div>
        <a href="logout.php" class="logout-btn">Cerrar sesión</a>
    </div>
</body>
</html>
