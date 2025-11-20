# Configuración de Ágorateca Escolar con XAMPP

## Pasos para configurar la base de datos:

### 1. Iniciar XAMPP
- Abre el Panel de Control de XAMPP
- Inicia los servicios **Apache** y **MySQL**

### 2. Crear la base de datos
Tienes dos opciones:

#### Opción A: Usando phpMyAdmin (Recomendado)
1. Abre tu navegador y ve a: `http://localhost/phpmyadmin`
2. Haz clic en la pestaña **"SQL"**
3. Abre el archivo `database.sql` que está en tu proyecto
4. Copia todo el contenido del archivo
5. Pégalo en el área de texto de phpMyAdmin
6. Haz clic en el botón **"Continuar"** o **"Go"**

#### Opción B: Importando el archivo
1. Abre tu navegador y ve a: `http://localhost/phpmyadmin`
2. Haz clic en **"Importar"** en el menú superior
3. Haz clic en **"Seleccionar archivo"**
4. Selecciona el archivo `database.sql`
5. Haz clic en **"Continuar"**

### 3. Verificar la configuración
- En `config.php` la configuración por defecto es:
  - Host: `localhost`
  - Usuario: `root`
  - Contraseña: *(vacía)*
  - Base de datos: `agorateca_escolar`

Si tu XAMPP tiene una configuración diferente, edita el archivo `config.php`.

### 4. Probar la aplicación
1. Asegúrate de que Apache y MySQL estén corriendo en XAMPP
2. Copia tu carpeta "ÁGORATECA ESCOLAR" a: `C:\xampp\htdocs\`
3. Abre tu navegador y ve a: `http://localhost/ÁGORATECA ESCOLAR/login.php`
4. Prueba registrar un nuevo usuario en: `http://localhost/ÁGORATECA ESCOLAR/register.php`

## Archivos creados:

- **config.php**: Configuración de conexión a la base de datos
- **login_process.php**: Procesa el inicio de sesión
- **register_process.php**: Procesa el registro de nuevos usuarios
- **dashboard.php**: Página principal después del login
- **logout.php**: Cierra la sesión del usuario
- **database.sql**: Script para crear la base de datos y tablas

## Características de seguridad implementadas:

✅ Contraseñas encriptadas con `password_hash()`
✅ Prepared statements para prevenir inyección SQL
✅ Validación de datos del formulario
✅ Sesiones PHP para mantener usuarios logueados
✅ Protección XSS con `htmlspecialchars()`

## Mensajes de error en login.php:

- `?error=empty`: Campos vacíos
- `?error=invalid`: Contraseña incorrecta
- `?error=notfound`: Usuario no encontrado

## Mensajes de error en register.php:

- `?error=empty`: Campos vacíos
- `?error=password_mismatch`: Las contraseñas no coinciden
- `?error=password_short`: Contraseña muy corta (mínimo 6 caracteres)
- `?error=email_exists`: El email ya está registrado
- `?error=username_exists`: El nombre de usuario ya existe
- `?error=database`: Error al guardar en la base de datos

## Próximos pasos opcionales:

- Agregar recuperación de contraseña
- Agregar roles de usuario (admin, estudiante, profesor)
- Implementar verificación de email
- Agregar más funcionalidades al dashboard
