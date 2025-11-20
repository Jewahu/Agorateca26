# 📧 Guía para Configurar PHPMailer con Gmail

## ✅ PHPMailer ya está instalado

La librería PHPMailer ya está en tu carpeta `PHPMailer/`. Ahora solo necesitas configurar tu correo.

---

## 🔧 Paso 1: Configurar Gmail

### 1. Activa la verificación en 2 pasos:
1. Ve a: https://myaccount.google.com/security
2. Busca **"Verificación en dos pasos"**
3. Actívala siguiendo las instrucciones

### 2. Crea una contraseña de aplicación:
1. Ve a: https://myaccount.google.com/apppasswords
2. En "Seleccionar app" elige **"Correo"**
3. En "Seleccionar dispositivo" elige **"Otro (nombre personalizado)"**
4. Escribe: **"Agorateca Escolar"**
5. Haz clic en **"Generar"**
6. **Copia la contraseña de 16 caracteres** que aparece (sin espacios)

---

## ⚙️ Paso 2: Configurar el archivo `email_config.php`

Abre el archivo `email_config.php` y edita estas líneas:

```php
define('SMTP_USERNAME', 'tu_correo@gmail.com'); // ← Pon tu correo de Gmail
define('SMTP_PASSWORD', 'xxxx xxxx xxxx xxxx'); // ← Pega la contraseña de aplicación
define('MAIL_FROM_EMAIL', 'tu_correo@gmail.com'); // ← El mismo correo
```

**Ejemplo:**
```php
define('SMTP_USERNAME', 'agorateca2025@gmail.com');
define('SMTP_PASSWORD', 'abcd efgh ijkl mnop'); // Contraseña de aplicación
define('MAIL_FROM_EMAIL', 'agorateca2025@gmail.com');
```

---

## 🧪 Paso 3: Probar en modo debug (opcional)

Si quieres ver cómo se ve el correo SIN enviarlo, activa el modo debug:

En `email_config.php`, cambia:
```php
define('EMAIL_DEBUG_MODE', true); // Cambia false a true
```

Esto mostrará el correo en pantalla en lugar de enviarlo.

---

## 🚀 Paso 4: Probar el registro

1. Ve a: `http://localhost/AGORATECA/register.php`
2. Regístrate con un correo que termine en `@cetis26.edu.mx`
3. Deberías ver la página de "Revisa tu correo"
4. Abre tu correo y busca el mensaje de verificación
5. **Revisa la carpeta de SPAM** si no lo ves
6. Haz clic en el enlace de verificación
7. ¡Listo! Ya puedes iniciar sesión

---

## 📋 Estructura de la base de datos actualizada

Ejecuta esto en phpMyAdmin si aún no lo hiciste:

```sql
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    is_verified TINYINT(1) NOT NULL DEFAULT 0,
    verification_token VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 📁 Archivos del sistema

### Nuevos archivos creados:
- ✅ `email_config.php` - Configuración del correo
- ✅ `register_process.php` - Actualizado con envío de correo
- ✅ `verify.php` - Verifica el token del correo
- ✅ `registration_pending.php` - Página de "Revisa tu correo"
- ✅ `resend_verification.php` - Reenviar correo de verificación
- ✅ `login_process.php` - Actualizado con validación de cuenta verificada

### Flujo completo:
1. Usuario se registra → `register.php` → `register_process.php`
2. Se envía correo de verificación → `registration_pending.php`
3. Usuario hace clic en el enlace del correo → `verify.php`
4. Cuenta verificada → Redirige a `dashboard.php`
5. Usuario puede iniciar sesión → `login.php` → `login_process.php`

---

## ⚠️ Problemas comunes

### ❌ "SMTP connect() failed"
- Verifica que MySQL y Apache estén corriendo en XAMPP
- Revisa que tu correo y contraseña estén correctos en `email_config.php`
- Asegúrate de usar la **contraseña de aplicación**, no tu contraseña normal

### ❌ "Account not verified"
- El usuario necesita hacer clic en el enlace del correo
- Puede reenviar el correo desde: `resend_verification.php`

### ❌ "Email send failed"
- Activa el modo debug para ver el error exacto
- Verifica tu conexión a internet
- Prueba con otro correo de Gmail

---

## 🔐 Seguridad implementada

✅ Verificación de correo obligatoria
✅ Token único de verificación
✅ Contraseñas encriptadas con `password_hash()`
✅ Prepared statements (anti SQL injection)
✅ Validación de dominio `@cetis26.edu.mx`
✅ Protección XSS con `htmlspecialchars()`

---

## 🎯 Siguiente paso

**Edita el archivo `email_config.php` con tu correo y contraseña de aplicación de Gmail.**

¡Y listo! Tu sistema de registro con verificación de correo estará funcionando. 🚀
