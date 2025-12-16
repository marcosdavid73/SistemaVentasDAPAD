<?php
// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'sistema_ventas');

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

// Función para formatear precio
function formatear_precio($precio)
{
    return '$' . number_format($precio, 2, ',', '.');
}

// Función para formatear fecha
function formatear_fecha($fecha)
{
    return date('d/m/Y H:i', strtotime($fecha));
}

// Iniciar sesión
session_start();

// Función para verificar si el usuario está logueado
function esta_logueado()
{
    return isset($_SESSION['usuario_id']);
}

// Función para verificar si es admin
function es_admin()
{
    return isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin';
}

// Función para redirigir
function redirigir($url)
{
    header("Location: " . $url);
    exit();
}

// Obtener rol del usuario actual
function obtener_rol()
{
    return $_SESSION['rol'] ?? '';
}

// Verificar si el usuario tiene permiso para acceder a un módulo
function tiene_permiso($modulo)
{
    $rol = obtener_rol();

    // Definir permisos por rol
    $permisos = array(
        'admin' => ['*'], // Acceso total
        'vendedor' => ['ventas', 'clientes', 'facturas', 'caja', 'reportes_ver'],
        'repositor' => ['productos', 'proveedores', 'reportes_ver'],
        'cliente' => ['catalogo']
    );

    // Admin tiene acceso a todo
    if ($rol === 'admin') {
        return true;
    }

    // Verificar si el rol tiene permiso para el módulo
    if (isset($permisos[$rol])) {
        return in_array($modulo, $permisos[$rol]);
    }

    return false;
}

// Verificar permiso y redirigir si no tiene acceso
function requiere_permiso($modulo)
{
    if (!esta_logueado()) {
        redirigir('login.php');
    }

    if (!tiene_permiso($modulo)) {
        header('HTTP/1.0 403 Forbidden');
        echo '<!DOCTYPE html>
        <html>
        <head>
            <title>Acceso Denegado</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <style>
                body { 
                    display: flex; 
                    align-items: center; 
                    justify-content: center; 
                    min-height: 100vh;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                }
                .error-card {
                    background: white;
                    padding: 3rem;
                    border-radius: 15px;
                    box-shadow: 0 10px 40px rgba(0,0,0,0.2);
                    text-align: center;
                    max-width: 500px;
                }
                .error-icon {
                    font-size: 5rem;
                    color: #dc3545;
                    margin-bottom: 1rem;
                }
            </style>
        </head>
        <body>
            <div class="error-card">
                <div class="error-icon">
                    <i class="fas fa-ban"></i>
                </div>
                <h1 class="text-danger mb-3">Acceso Denegado</h1>
                <p class="text-muted mb-4">No tienes permisos para acceder a este módulo.</p>
                <a href="dashboard.php" class="btn btn-primary">
                    <i class="fas fa-home me-2"></i>Volver al Dashboard
                </a>
            </div>
            <script src="https://kit.fontawesome.com/your-code.js"></script>
        </body>
        </html>';
        exit();
    }
}

// Actualizar último acceso del usuario
function actualizar_ultimo_acceso($usuario_id)
{
    global $conn;
    $sql = "UPDATE usuarios SET ultimo_acceso = NOW() WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
}
