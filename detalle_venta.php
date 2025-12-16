<?php
require_once 'config.php';

$venta_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sql_venta = "SELECT v.*, CONCAT(c.nombre, ' ', c.apellido) as cliente_nombre,
              c.dni, c.email, c.telefono, c.direccion,
              u.nombre as vendedor
              FROM ventas v
              INNER JOIN clientes c ON v.cliente_id = c.id
              INNER JOIN usuarios u ON v.usuario_id = u.id
              WHERE v.id = ?";
$stmt = $conn->prepare($sql_venta);
$stmt->bind_param("i", $venta_id);
$stmt->execute();
$venta = $stmt->get_result()->fetch_assoc();

if (!$venta) {
    header("Location: ventas.php");
    exit();
}

$sql_detalles = "SELECT dv.*, p.nombre as producto_nombre
                 FROM detalle_ventas dv
                 INNER JOIN productos p ON dv.producto_id = p.id
                 WHERE dv.venta_id = ?";
$stmt_det = $conn->prepare($sql_detalles);
$stmt_det->bind_param("i", $venta_id);
$stmt_det->execute();
$detalles = $stmt_det->get_result();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Venta #<?php echo $venta_id; ?> - Sistema de Ventas</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="style-minimal.css?v=2" rel="stylesheet">
    <style>
        .invoice-header {
            background: var(--primary-color);
            color: white;
            padding: 2rem;
            border-radius: 8px 8px 0 0;
        }

        .total-box {
            background-color: #f1f5f9;
            padding: 1.5rem;
            border-radius: 8px;
            text-align: right;
        }

        .total-amount {
            font-size: 2rem;
            font-weight: 600;
            color: var(--success-color);
        }

        @media print {
            #sidebar-wrapper,
            .topbar,
            .no-print {
                display: none !important;
            }

            #content-wrapper {
                margin: 0 !important;
            }

            .card {
                box-shadow: none !important;
            }
        }
    </style>
</head>

<body>
    <div id="wrapper">
        <?php include 'sidebar.php'; ?>

        <div id="content-wrapper">
            <?php include 'topbar.php'; ?>

            <div class="container-fluid">
                <div class="d-sm-flex align-items-center justify-content-between mb-4 no-print">
                    <h1 class="h3 mb-0 text-gray-800">Detalle de Venta</h1>
                    <div class="btn-group">
                        <a href="ventas.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                        <button onclick="window.print()" class="btn btn-primary">
                            <i class="fas fa-print"></i> Imprimir
                        </button>
                    </div>
                </div>

                <div class="card">
                    <div class="invoice-header">
                        <div class="row">
                            <div class="col-md-6">
                                <h2><i class="fas fa-shopping-cart"></i> VENTA #<?php echo str_pad($venta_id, 6, '0', STR_PAD_LEFT); ?></h2>
                                <p class="mb-0">
                                    <i class="fas fa-calendar"></i> <?php echo formatear_fecha($venta['fecha_venta']); ?>
                                </p>
                            </div>
                            <div class="col-md-6 text-end">
                                <h4>SISTEMA DE VENTAS</h4>
                                <p class="mb-0">
                                    <small>Vendedor: <?php echo $venta['vendedor']; ?></small>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Información del Cliente -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5 class="text-primary"><i class="fas fa-user"></i> Información del Cliente</h5>
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Nombre:</strong></td>
                                        <td><?php echo $venta['cliente_nombre']; ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>DNI:</strong></td>
                                        <td><?php echo $venta['dni']; ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Email:</strong></td>
                                        <td><?php echo $venta['email'] ?? '-'; ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Teléfono:</strong></td>
                                        <td><?php echo $venta['telefono'] ?? '-'; ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Dirección:</strong></td>
                                        <td><?php echo $venta['direccion'] ?? '-'; ?></td>
                                    </tr>
                                </table>
                            </div>

                            <div class="col-md-6">
                                <h5 class="text-primary"><i class="fas fa-info-circle"></i> Información de la Venta</h5>
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Método de Pago:</strong></td>
                                        <td>
                                            <span class="badge bg-info">
                                                <?php
                                                $iconos_pago = [
                                                    'efectivo' => 'fa-money-bill-wave',
                                                    'tarjeta' => 'fa-credit-card',
                                                    'transferencia' => 'fa-exchange-alt'
                                                ];
                                                echo '<i class="fas ' . ($iconos_pago[$venta['metodo_pago']] ?? 'fa-wallet') . '"></i> ';
                                                echo ucfirst($venta['metodo_pago']);
                                                ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Estado:</strong></td>
                                        <td>
                                            <?php
                                            $badge_color = 'success';
                                            switch ($venta['estado']) {
                                                case 'pendiente':
                                                    $badge_color = 'warning';
                                                    break;
                                                case 'cancelada':
                                                    $badge_color = 'danger';
                                                    break;
                                            }
                                            ?>
                                            <span class="badge bg-<?php echo $badge_color; ?>">
                                                <?php echo ucfirst($venta['estado']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- Detalle de Productos -->
                        <h5 class="text-primary mb-3"><i class="fas fa-box"></i> Productos</h5>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Producto</th>
                                        <th class="text-center">Cantidad</th>
                                        <th class="text-end">Precio Unitario</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $subtotal_total = 0;
                                    while ($detalle = $detalles->fetch_assoc()):
                                        $subtotal_total += $detalle['subtotal'];
                                    ?>
                                        <tr>
                                            <td><?php echo $detalle['producto_nombre']; ?></td>
                                            <td class="text-center">
                                                <span class="badge bg-primary"><?php echo $detalle['cantidad']; ?></span>
                                            </td>
                                            <td class="text-end"><?php echo formatear_precio($detalle['precio_unitario']); ?></td>
                                            <td class="text-end fw-bold text-success">
                                                <?php echo formatear_precio($detalle['subtotal']); ?>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Total -->
                        <div class="row mt-4">
                            <div class="col-md-8"></div>
                            <div class="col-md-4">
                                <div class="total-box">
                                    <h6 class="text-muted">TOTAL A PAGAR</h6>
                                    <div class="total-amount">
                                        <?php echo formatear_precio($venta['total']); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notas adicionales -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    <strong>Nota:</strong> Esta es una copia digital de la venta realizada.
                                    Para cualquier consulta o reclamo, comunicarse con el área de ventas.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>
