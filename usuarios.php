<?php
require_once 'config.php';

if (!esta_logueado()) {
    redirigir('login.php');
}

if (!es_admin()) {
    header('HTTP/1.0 403 Forbidden');
    die('Acceso denegado. Solo administradores pueden gestionar usuarios.');
}

$mensaje = '';
$tipo_mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    if ($accion === 'crear') {
        $nombre = limpiar_entrada($_POST['nombre']);
        $email = limpiar_entrada($_POST['email']);
        $password = $_POST['password'];
        $rol = limpiar_entrada($_POST['rol']);

        $check = $conn->query("SELECT id FROM usuarios WHERE email = '$email'");
        if ($check->num_rows > 0) {
            $mensaje = 'El email ya está registrado';
            $tipo_mensaje = 'danger';
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $nombre, $email, $password_hash, $rol);

            if ($stmt->execute()) {
                $mensaje = 'Usuario creado exitosamente';
                $tipo_mensaje = 'success';
            } else {
                $mensaje = 'Error al crear usuario: ' . $conn->error;
                $tipo_mensaje = 'danger';
            }
        }
    } elseif ($accion === 'editar') {
        $id = intval($_POST['id']);
        $nombre = limpiar_entrada($_POST['nombre']);
        $email = limpiar_entrada($_POST['email']);
        $rol = limpiar_entrada($_POST['rol']);
        $password = $_POST['password'] ?? '';

        $check = $conn->query("SELECT id FROM usuarios WHERE email = '$email' AND id != $id");
        if ($check->num_rows > 0) {
            $mensaje = 'El email ya está registrado por otro usuario';
            $tipo_mensaje = 'danger';
        } else {
            if (!empty($password)) {
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $sql = "UPDATE usuarios SET nombre = ?, email = ?, password = ?, rol = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssi", $nombre, $email, $password_hash, $rol, $id);
            } else {
                $sql = "UPDATE usuarios SET nombre = ?, email = ?, rol = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssi", $nombre, $email, $rol, $id);
            }

            if ($stmt->execute()) {
                $mensaje = 'Usuario actualizado exitosamente';
                $tipo_mensaje = 'success';
            } else {
                $mensaje = 'Error al actualizar usuario: ' . $conn->error;
                $tipo_mensaje = 'danger';
            }
        }
    } elseif ($accion === 'cambiar_estado') {
        $id = intval($_POST['id']);
        $estado = intval($_POST['estado']);

        $sql = "UPDATE usuarios SET estado = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $estado, $id);

        if ($stmt->execute()) {
            $mensaje = $estado ? 'Usuario activado' : 'Usuario desactivado';
            $tipo_mensaje = 'success';
        } else {
            $mensaje = 'Error al cambiar estado';
            $tipo_mensaje = 'danger';
        }
    } elseif ($accion === 'eliminar') {
        $id = intval($_POST['id']);

        if ($id == 1) {
            $mensaje = 'No se puede eliminar el usuario administrador principal';
            $tipo_mensaje = 'danger';
        } else {
            $sql = "DELETE FROM usuarios WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                $mensaje = 'Usuario eliminado exitosamente';
                $tipo_mensaje = 'success';
            } else {
                $mensaje = 'Error al eliminar usuario: ' . $conn->error;
                $tipo_mensaje = 'danger';
            }
        }
    }
}

$usuarios = $conn->query("SELECT * FROM usuarios ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - Sistema de Ventas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style-minimal.css?v=2">
    <style>
        #wrapper {
            display: flex;
        }

        #sidebar-wrapper {
            min-height: 100vh;
            width: 224px;
            background: var(--primary-color);
        }

        #content-wrapper {
            flex: 1;
            min-width: 0;
        }

        .navbar {
            background: var(--primary-color);
        }

        .card-header {
            background: var(--primary-color);
            color: white;
            border-radius: 10px 10px 0 0 !important;
            padding: 1rem 1.5rem;
        }

        .badge-rol {
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
            font-weight: 600;
            border-radius: 20px;
        }

        .badge-admin {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .badge-vendedor {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .badge-repositor {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .badge-cliente {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        }

        .btn-action {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            margin: 0 0.1rem;
        }

        .table thead th {
            background-color: #f8f9fc;
            color: var(--dark-color);
            font-weight: 600;
            border-bottom: 2px solid var(--primary-color);
        }

        .modal-header {
            background: var(--primary-color);
            color: white;
        }

        .estado-activo {
            color: var(--success-color);
        }

        .estado-inactivo {
            color: var(--danger-color);
        }
    </style>
</head>

<body>
    <div id="wrapper">
        <?php include 'sidebar.php'; ?>

        <div id="content-wrapper">
            <nav class="navbar navbar-expand topbar mb-4 static-top" style="display: flex; justify-content: space-between; align-items: center; padding: 0 1.5rem;">
                <!-- 🔍 BÚSQUEDA -->
                <div class="search-container" style="flex: 1; max-width: 600px; margin-right: 1rem;">
                    <form class="d-none d-sm-inline-block form-inline my-2 my-md-0" style="width: 100%;" action="dashboard.php" method="GET">
                        <div class="input-group">
                            <input type="text"
                                class="form-control bg-light border-0 small"
                                placeholder="Buscar productos, clientes, ventas..."
                                name="buscar"
                                style="border-radius: 10rem 0 0 10rem; padding-left: 1rem;">
                            <button type="submit" class="btn btn-primary" style="border-radius: 0 10rem 10rem 0; padding: 0 1rem;">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small" style="margin-right: 0.5rem;">
                                <strong><?php echo htmlspecialchars($_SESSION['nombre'] ?? 'Usuario'); ?></strong>
                                <br>
                                <small style="color: #858796;"><?php echo ucfirst($_SESSION['rol'] ?? ''); ?></small>
                            </span>
                            <i class="fas fa-user-circle fa-2x" style="color: #858796;"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userDropdown" style="min-width: 200px;">
                            <li><h6 class="dropdown-header" style="background: var(--primary-color); color: white; margin: -0.5rem -1rem 0.5rem; padding: 0.75rem 1rem;">
                                <i class="fas fa-user-circle"></i> Mi Cuenta
                            </h6></li>
                            <li><a class="dropdown-item" href="index.php">
                                <i class="fas fa-store fa-sm fa-fw mr-2" style="color: #4e73df; margin-right: 0.5rem;"></i>
                                Ver Catálogo Público
                            </a></li>
                            <li><a class="dropdown-item" href="#" style="pointer-events: none; opacity: 0.6;">
                                <i class="fas fa-id-card fa-sm fa-fw mr-2" style="color: #858796; margin-right: 0.5rem;"></i>
                                Mi Perfil (Próximamente)
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="cerrar_sesion.php">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2" style="color: #e74a3b; margin-right: 0.5rem;"></i>
                                Cerrar Sesión
                            </a></li>
                        </ul>
                    </li>
                </ul>
            </nav>

            <div class="container-fluid">
        <?php if ($mensaje): ?>
            <div class="alert alert-<?php echo $tipo_mensaje; ?> alert-dismissible fade show" role="alert">
                <i class="fas fa-<?php echo $tipo_mensaje === 'success' ? 'check-circle' : 'exclamation-triangle'; ?> me-2"></i>
                <?php echo $mensaje; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-users me-2"></i>Gestión de Usuarios
                        </h5>
                        <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#modalCrearUsuario">
                            <i class="fas fa-user-plus me-2"></i>Nuevo Usuario
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>Rol</th>
                                        <th>Estado</th>
                                        <th>Último Acceso</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($usuario = $usuarios->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo $usuario['id']; ?></td>
                                            <td>
                                                <i class="fas fa-user-circle me-2 text-primary"></i>
                                                <?php echo htmlspecialchars($usuario['nombre']); ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                                            <td>
                                                <span class="badge badge-rol badge-<?php echo $usuario['rol']; ?>">
                                                    <?php echo ucfirst($usuario['rol']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <i class="fas fa-circle <?php echo $usuario['estado'] ? 'estado-activo' : 'estado-inactivo'; ?>"></i>
                                                <?php echo $usuario['estado'] ? 'Activo' : 'Inactivo'; ?>
                                            </td>
                                            <td>
                                                <?php
                                                if ($usuario['ultimo_acceso']) {
                                                    echo date('d/m/Y H:i', strtotime($usuario['ultimo_acceso']));
                                                } else {
                                                    echo '<span class="text-muted">Nunca</span>';
                                                }
                                                ?>
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-primary btn-action"
                                                    onclick="editarUsuario(<?php echo htmlspecialchars(json_encode($usuario)); ?>)">
                                                    <i class="fas fa-edit"></i>
                                                </button>

                                                <form method="POST" style="display: inline;">
                                                    <input type="hidden" name="accion" value="cambiar_estado">
                                                    <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
                                                    <input type="hidden" name="estado" value="<?php echo $usuario['estado'] ? 0 : 1; ?>">
                                                    <button type="submit" class="btn btn-sm btn-<?php echo $usuario['estado'] ? 'warning' : 'success'; ?> btn-action"
                                                        <?php echo $usuario['id'] == 1 ? 'disabled' : ''; ?>>
                                                        <i class="fas fa-<?php echo $usuario['estado'] ? 'ban' : 'check'; ?>"></i>
                                                    </button>
                                                </form>

                                                <?php if ($usuario['id'] != 1): ?>
                                                    <button class="btn btn-sm btn-danger btn-action"
                                                        onclick="confirmarEliminar(<?php echo $usuario['id']; ?>, '<?php echo htmlspecialchars($usuario['nombre']); ?>')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Crear Usuario -->
    <div class="modal fade" id="modalCrearUsuario" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-user-plus me-2"></i>Crear Nuevo Usuario
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="accion" value="crear">
                        <div class="mb-3">
                            <label class="form-label">Nombre Completo</label>
                            <input type="text" class="form-control" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contraseña</label>
                            <input type="password" class="form-control" name="password" required minlength="6">
                            <small class="text-muted">Mínimo 6 caracteres</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Rol</label>
                            <select class="form-select" name="rol" required>
                                <option value="admin">Administrador - Acceso total</option>
                                <option value="vendedor" selected>Vendedor - Ventas, clientes, caja</option>
                                <option value="repositor">Repositor - Productos, proveedores</option>
                                <option value="cliente">Cliente - Solo visualización catálogo</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Crear Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar Usuario -->
    <div class="modal fade" id="modalEditarUsuario" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-user-edit me-2"></i>Editar Usuario
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="accion" value="editar">
                        <input type="hidden" name="id" id="edit_id">
                        <div class="mb-3">
                            <label class="form-label">Nombre Completo</label>
                            <input type="text" class="form-control" name="nombre" id="edit_nombre" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" id="edit_email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nueva Contraseña (dejar vacío para no cambiar)</label>
                            <input type="password" class="form-control" name="password" minlength="6">
                            <small class="text-muted">Solo completar si desea cambiar la contraseña</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Rol</label>
                            <select class="form-select" name="rol" id="edit_rol" required>
                                <option value="admin">Administrador - Acceso total</option>
                                <option value="vendedor">Vendedor - Ventas, clientes, caja</option>
                                <option value="repositor">Repositor - Productos, proveedores</option>
                                <option value="cliente">Cliente - Solo visualización catálogo</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Form para eliminar (oculto) -->
    <form id="formEliminar" method="POST" style="display: none;">
        <input type="hidden" name="accion" value="eliminar">
        <input type="hidden" name="id" id="delete_id">
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editarUsuario(usuario) {
            document.getElementById('edit_id').value = usuario.id;
            document.getElementById('edit_nombre').value = usuario.nombre;
            document.getElementById('edit_email').value = usuario.email;
            document.getElementById('edit_rol').value = usuario.rol;

            const modal = new bootstrap.Modal(document.getElementById('modalEditarUsuario'));
            modal.show();
        }

        function confirmarEliminar(id, nombre) {
            if (confirm(`¿Estás seguro de eliminar al usuario "${nombre}"?\n\nEsta acción no se puede deshacer.`)) {
                document.getElementById('delete_id').value = id;
                document.getElementById('formEliminar').submit();
            }
        }
    </script>
            </div>
        </div>
    </div>
</body>

</html>
