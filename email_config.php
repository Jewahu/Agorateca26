<?php
// Configuración del correo electrónico

// === CONFIGURACIÓN PARA GMAIL ===
// Si usas Gmail, necesitas:
// 1. Activar la verificación en 2 pasos
// 2. Crear una "Contraseña de aplicación" en: https://myaccount.google.com/apppasswords
// 3. Usar esa contraseña aquí (no tu contraseña normal de Gmail)

define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_USERNAME', 'agoratecaprueba@gmail.com'); // Cambia esto por tu correo de Gmail
define('SMTP_PASSWORD', 'rqdc ivem ywmi joxj'); // Cambia esto por tu contraseña de aplicación
define('SMTP_PORT', 587);
define('SMTP_SECURE', 'tls'); // 'tls' o 'ssl'

define('MAIL_FROM_EMAIL', 'agoratecaprueba@gmail.com'); // El mismo correo
define('MAIL_FROM_NAME', 'Ágorateca Escolar');

// === OTRAS OPCIONES ===

// Outlook/Hotmail:
// SMTP_HOST: smtp.office365.com
// SMTP_PORT: 587
// SMTP_SECURE: tls

// Yahoo:
// SMTP_HOST: smtp.mail.yahoo.com
// SMTP_PORT: 465 o 587
// SMTP_SECURE: ssl (465) o tls (587)

// Para habilitar modo de prueba (no envía correos reales, solo los muestra en pantalla)
define('EMAIL_DEBUG_MODE', false); // Cambia a true para ver el contenido sin enviar
?>
