<?php
session_start();

if (isset($_SESSION['usuario_id'])) {
    header("Location: dashboard.php");
    exit();
}

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'sistema_ventas');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$error = '';
$debug_info = ''; // Para mostrar información de depuración

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = 'Por favor complete todos los campos';
    } else {
        $sql = "SELECT * FROM usuarios WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $usuario = $result->fetch_assoc();


            if (password_verify($password, $usuario['password'])) {
                if ($usuario['estado'] == 0) {
                    $error = 'Tu cuenta ha sido desactivada. Contacta al administrador.';
                } else {
                    $_SESSION['usuario_id'] = $usuario['id'];
                    $_SESSION['usuario_nombre'] = $usuario['nombre'];
                    $_SESSION['usuario_email'] = $usuario['email'];
                    $_SESSION['rol'] = $usuario['rol'];

                    $update_sql = "UPDATE usuarios SET ultimo_acceso = NOW() WHERE id = ?";
                    $update_stmt = $conn->prepare($update_sql);
                    $update_stmt->bind_param("i", $usuario['id']);
                    $update_stmt->execute();

                    header("Location: dashboard.php");
                    exit();
                }
            } else {
                $error = 'Contraseña incorrecta';
            }
        } else {
            $error = 'Usuario no encontrado';
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Ventas</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style-minimal.css">
    <style>
        body {
            background: var(--bg-secondary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            max-width: 450px;
            width: 100%;
            padding: 20px;
        }

        .card-header {
            background: var(--primary-color);
            color: var(--text-white);
            border-radius: var(--border-radius-lg) var(--border-radius-lg) 0 0 !important;
            padding: 2rem;
            text-align: center;
        }

        .card-header h1 {
            margin: 0;
            font-size: 1.75rem;
            font-weight: 700;
        }

        .card-header .icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .card-body {
            padding: 2.5rem;
        }

        .input-group-text {
            background-color: var(--gray-50);
            border: 1px solid var(--border-color);
            color: var(--text-secondary);
        }

        .btn-primary {
            width: 100%;
            padding: 0.85rem;
            font-weight: 600;
        }

        .login-info {
            background-color: #f8f9fc;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-top: 1rem;
            font-size: 0.875rem;
        }

        .password-toggle {
            cursor: pointer;
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #858796;
            z-index: 10;
        }

        .password-wrapper {
            position: relative;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="card">
            <div class="card-header">
                <div class="icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h1>Sistema de Ventas</h1>
                <p class="mb-0">Inicia sesión para continuar</p>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle"></i> <?php echo $error; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if ($debug_info): ?>
                    <div class="alert alert-info">
                        <?php echo $debug_info; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label">Correo Electrónico</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="email"
                                class="form-control"
                                name="email"
                                placeholder="usuario@ejemplo.com"
                                value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : 'admin@sistema.com'; ?>"
                                required
                                autofocus>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Contraseña</label>
                        <div class="password-wrapper">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password"
                                    class="form-control"
                                    name="password"
                                    id="password"
                                    placeholder="••••••••"
                                    required>
                            </div>
                            <span class="password-toggle" onclick="togglePassword()">
                                <i class="fas fa-eye" id="toggleIcon"></i>
                            </span>
                        </div>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember">
                        <label class="form-check-label" for="remember">Recordarme</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                    </button>
                </form>

                <div class="login-info mt-4">
                    <strong><i class="fas fa-info-circle"></i> Credenciales de prueba:</strong><br>
                    <div class="mt-2">
                        <strong>Email:</strong> admin@sistema.com<br>
                        <strong>Contraseña:</strong> admin123
                    </div>
                    <div class="mt-3">
                        <button class="btn btn-sm btn-outline-primary" onclick="autoFill()">
                            <i class="fas fa-magic"></i> Autocompletar
                        </button>
                    </div>
                </div>
                
                <div class="text-center mt-4">
                    <a href="index.php" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver al Catálogo
                    </a>
                </div>
            </div>
        </div>

        <div class="text-center mt-4 text-white">
            <small>&copy; 2025 Sistema de Ventas. Todos los derechos reservados.</small>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        function autoFill() {
            document.querySelector('input[name="email"]').value = 'admin@sistema.com';
            document.querySelector('input[name="password"]').value = 'admin123';
        }
    </script>
</body>

</html>
