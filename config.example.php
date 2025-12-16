<?php
// ==========================================
// CONFIGURACIÓN DE BASE DE DATOS
// ==========================================
// INSTRUCCIONES: 
// 1. Copia este archivo como "config.php"
// 2. Modifica los valores según tu entorno
// 3. Importa database.sql o sistema_ventas.sql en tu MySQL

define('DB_HOST', 'localhost');        // Host de la base de datos
define('DB_USER', 'root');             // Usuario de MySQL
define('DB_PASS', '');                 // Contraseña de MySQL (vacío en XAMPP/Laragon)
define('DB_NAME', 'sistema_ventas');   // Nombre de la base de datos

// ==========================================
// CONFIGURACIÓN DE LA APLICACIÓN
// ==========================================
define('APP_NAME', 'Sistema de Ventas');
define('APP_URL', 'http://localhost/sistema/');

// ==========================================
// CONFIGURACIÓN DE UPLOADS
// ==========================================
define('UPLOAD_DIR', 'uploads/productos/');
define('MAX_FILE_SIZE', 2097152); // 2MB en bytes

// ==========================================
// NO MODIFICAR DEBAJO DE ESTA LÍNEA
// ==========================================

// Crear conexión
try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // Verificar conexión
    if ($conn->connect_error) {
        throw new Exception("Error de conexión: " . $conn->connect_error);
    }

    // Establecer charset UTF-8
    $conn->set_charset("utf8");
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}

// Función para prevenir SQL Injection
function limpiar_entrada($data)
{
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $conn->real_escape_string($data);
}

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Función para verificar si el usuario está logueado
function esta_logueado()
{
    return isset($_SESSION['usuario_id']);
}

// Función para verificar el rol del usuario
function obtener_rol()
{
    return $_SESSION['usuario_rol'] ?? null;
}

// Función para verificar si es administrador
function es_admin()
{
    return obtener_rol() === 'admin';
}

// Matriz de permisos por rol
function obtener_permisos()
{
    return [
        'admin' => ['productos', 'clientes', 'ventas', 'facturas', 'caja', 'reportes', 'usuarios', 'proveedores'],
        'vendedor' => ['clientes', 'ventas', 'facturas', 'caja', 'reportes'],
        'repositor' => ['productos', 'proveedores', 'reportes'],
        'cliente' => []
    ];
}

// Función para verificar permisos
function tiene_permiso($modulo)
{
    if (!esta_logueado()) {
        return false;
    }

    $rol = obtener_rol();
    $permisos = obtener_permisos();

    return isset($permisos[$rol]) && in_array($modulo, $permisos[$rol]);
}

// Función para requerir permiso (redirige si no tiene acceso)
function requiere_permiso($modulo)
{
    if (!esta_logueado()) {
        header("Location: login.php");
        exit();
    }

    if (!tiene_permiso($modulo)) {
        http_response_code(403);
        echo "<!DOCTYPE html>
        <html lang='es'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Acceso Denegado</title>
            <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
        </head>
        <body class='bg-light'>
            <div class='container mt-5'>
                <div class='row justify-content-center'>
                    <div class='col-md-6'>
                        <div class='card shadow'>
                            <div class='card-body text-center py-5'>
                                <i class='fas fa-lock fa-4x text-danger mb-4'></i>
                                <h2 class='text-danger'>Acceso Denegado</h2>
                                <p class='text-muted'>No tienes permisos para acceder a este módulo.</p>
                                <a href='dashboard.php' class='btn btn-primary mt-3'>Volver al Dashboard</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </body>
        </html>";
        exit();
    }
}

// Función para redirigir
function redirigir($url)
{
    header("Location: $url");
    exit();
}

// Función para formatear precio
function formatear_precio($precio)
{
    return '$' . number_format($precio, 2, ',', '.');
}

// Función para formatear fecha
function formatear_fecha($fecha)
{
    return date('d/m/Y', strtotime($fecha));
}

// Función para formatear fecha y hora
function formatear_fecha_hora($fecha)
{
    return date('d/m/Y H:i', strtotime($fecha));
}
