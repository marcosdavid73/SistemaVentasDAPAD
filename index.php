<?php
require_once 'config.php';


$busqueda = $_GET['buscar'] ?? '';
$categoria_id = $_GET['categoria'] ?? '';

$categorias_query = "SELECT c.*, ci.icono 
                     FROM categorias c 
                     LEFT JOIN categorias_iconos ci ON c.id = ci.categoria_id 
                     WHERE c.estado=1 
                     ORDER BY c.nombre";
$categorias = $conn->query($categorias_query);

$sql = "SELECT p.*, c.nombre as categoria_nombre 
        FROM productos p 
        LEFT JOIN categorias c ON p.categoria_id = c.id 
        WHERE p.estado = 1";

$params = [];
$types = '';

if (!empty($busqueda)) {
    $sql .= " AND (p.nombre LIKE ? OR p.codigo LIKE ? OR p.descripcion LIKE ?)";
    $busqueda_param = "%$busqueda%";
    $params[] = &$busqueda_param;
    $params[] = &$busqueda_param;
    $params[] = &$busqueda_param;
    $types .= 'sss';
}

if (!empty($categoria_id)) {
    $sql .= " AND p.categoria_id = ?";
    $params[] = &$categoria_id;
    $types .= 'i';
}

$sql .= " ORDER BY p.nombre";

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    array_unshift($params, $types);
    call_user_func_array([$stmt, 'bind_param'], $params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Productos - Sistema de Ventas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style-minimal.css">
    <style>
        .product-card {
            height: 100%;
        }

        .product-card:hover {
            transform: translateY(-2px);
        }

        .product-image {
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, var(--gray-100) 0%, var(--gray-200) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .product-image img {
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
        }

        .product-image i {
            font-size: 4rem;
            color: var(--gray-400);
        }

        .product-body {
            padding: 1.5rem;
        }

        .product-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .product-category {
            display: inline-block;
            padding: 0.3rem 0.85rem;
            background-color: var(--gray-100);
            color: var(--text-secondary);
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .product-stock {
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }

        .stock-disponible {
            color: var(--success-color);
            font-weight: 600;
        }

        .stock-bajo {
            color: var(--warning-color);
            font-weight: 600;
        }

        .stock-critico {
            color: var(--danger-color);
            font-weight: 600;
        }

        .filter-section {
            background: var(--bg-primary);
            padding: 2rem;
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-sm);
            margin-bottom: 2rem;
            border: 1px solid var(--border-color);
        }

        .category-pills {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            padding: 0.5rem 0;
        }

        .category-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.65rem 1.25rem;
            background: var(--bg-primary);
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .category-pill:hover {
            background: var(--gray-50);
            color: var(--primary-color);
            border-color: var(--primary-color);
            transform: translateY(-1px);
        }

        .category-pill.active {
            background: var(--primary-color);
            color: var(--text-white);
            border-color: var(--primary-color);
        }

        .category-pill i {
            font-size: 1rem;
        }
    </style>
</head>


<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-shopping-bag me-2"></i>Catálogo de Productos
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if (esta_logueado()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard.php">
                                <i class="fas fa-tachometer-alt me-1"></i>Panel de Control
                            </a>
                        </li>
                        <li class="nav-item">
                            <span class="nav-link">
                                <i class="fas fa-user me-1"></i><?php echo $_SESSION['usuario_nombre']; ?>
                            </span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="cerrar_sesion.php">
                                <i class="fas fa-sign-out-alt me-1"></i>Cerrar Sesión
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link btn btn-light text-primary px-4" href="login.php">
                                <i class="fas fa-user-shield me-1"></i>Acceso Personal
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <!-- Secciones de Categorías -->
        <div class="row mb-4">
            <div class="col-12">
                <h4 class="mb-3" style="font-weight: 600; color: var(--text-primary);">
                    <i class="fas fa-th-large me-2"></i>Categorías
                </h4>
                <div class="category-pills">
                    <a href="index.php" class="category-pill <?php echo empty($categoria_id) ? 'active' : ''; ?>">
                        <i class="fas fa-border-all"></i>
                        <span>Todos</span>
                    </a>
                    <?php 
                    $categorias_temp = $conn->query($categorias_query);
                    while ($cat = $categorias_temp->fetch_assoc()): 
                        $icono = $cat['icono'] ?? 'fa-cube';
                    ?>
                        <a href="?categoria=<?php echo $cat['id']; ?>" 
                           class="category-pill <?php echo $categoria_id == $cat['id'] ? 'active' : ''; ?>">
                            <i class="fas <?php echo $icono; ?>"></i>
                            <span><?php echo htmlspecialchars($cat['nombre']); ?></span>
                        </a>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="filter-section">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label class="form-label">
                        <i class="fas fa-search me-2"></i>Buscar productos
                    </label>
                    <input type="text" class="form-control search-input" name="buscar"
                        placeholder="Nombre, código o descripción..."
                        value="<?php echo htmlspecialchars($busqueda); ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">
                        <i class="fas fa-filter me-2"></i>Filtrar por Categoría
                    </label>
                    <select class="form-select" name="categoria">
                        <option value="">Todas las categorías</option>
                        <?php 
                        $categorias_select = $conn->query($categorias_query);
                        while ($cat = $categorias_select->fetch_assoc()): 
                        ?>
                            <option value="<?php echo $cat['id']; ?>"
                                <?php echo $categoria_id == $cat['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['nombre']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-2"></i>Filtrar
                    </button>
                </div>
                <?php if (!empty($busqueda) || !empty($categoria_id)): ?>
                    <div class="col-12">
                        <a href="index.php" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-times me-2"></i>Limpiar filtros
                        </a>
                    </div>
                <?php endif; ?>
            </form>
        </div>

        <!-- Productos -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($producto = $result->fetch_assoc()): ?>
                    <div class="col">
                        <div class="product-card">
                            <div class="product-image">
                                <?php if (!empty($producto['imagen']) && file_exists($producto['imagen'])): ?>
                                    <img src="<?php echo htmlspecialchars($producto['imagen']); ?>" alt="<?php echo htmlspecialchars($producto['nombre'] ?? ''); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                <?php else: ?>
                                    <i class="fas fa-box"></i>
                                <?php endif; ?>
                            </div>
                            <div class="product-body">
                                <span class="product-category">
                                    <?php echo htmlspecialchars($producto['categoria_nombre'] ?? 'Sin categoría'); ?>
                                </span>
                                <h5 class="product-title">
                                    <?php echo htmlspecialchars($producto['nombre'] ?? ''); ?>
                                </h5>
                                <p class="text-muted mb-2" style="font-size: 0.85rem;">
                                    <strong>Código:</strong> <?php echo htmlspecialchars($producto['codigo'] ?? 'N/A'); ?>
                                </p>
                                <?php if (!empty($producto['descripcion'])): ?>
                                    <p class="text-muted mb-2" style="font-size: 0.85rem;">
                                        <?php echo htmlspecialchars(substr($producto['descripcion'], 0, 80)); ?>
                                        <?php echo strlen($producto['descripcion']) > 80 ? '...' : ''; ?>
                                    </p>
                                <?php endif; ?>

                                <div class="product-stock">
                                    <?php
                                    $stock_class = 'stock-disponible';
                                    $stock_icon = 'check-circle';
                                    $stock_text = 'Disponible';

                                    $stock = $producto['stock'] ?? 0;
                                    $stock_minimo = $producto['stock_minimo'] ?? 0;

                                    if ($stock_minimo > 0) {
                                        if ($stock <= $stock_minimo) {
                                            $stock_class = 'stock-critico';
                                            $stock_icon = 'exclamation-triangle';
                                            $stock_text = 'Stock crítico';
                                        } elseif ($stock <= $stock_minimo * 2) {
                                            $stock_class = 'stock-bajo';
                                            $stock_icon = 'exclamation-circle';
                                            $stock_text = 'Stock bajo';
                                        }
                                    }
                                    ?>
                                    <i class="fas fa-<?php echo $stock_icon; ?> me-2 <?php echo $stock_class; ?>"></i>
                                    <span class="<?php echo $stock_class; ?>">
                                        <?php echo $stock_text; ?> (<?php echo $stock; ?> unidades)
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info text-center" role="alert">
                        <i class="fas fa-info-circle fa-3x mb-3"></i>
                        <h4>No se encontraron productos</h4>
                        <p class="mb-0">Intenta ajustar los filtros de búsqueda.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
