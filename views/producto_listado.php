<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PYME - Catálogo y Cotizador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>PYME | Módulo 1: Catálogo Digital</h2>
        <a href="ProductoController.php?accion=crear" class="btn btn-primary fw-bold">+ Registrar Producto</a>
    </div>

    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-<?= htmlspecialchars($tipoAlert) ?> alert-dismissible fade show">
            <?= htmlspecialchars($mensaje) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- COTIZADOR AUTOMÁTICO DE VENTAS -->
    <div class="card mb-4 border-info shadow-sm">
        <div class="card-header bg-info text-white fw-bold">Cotizador Automático de Ventas (IVA 13% Incluido)</div>
        <div class="card-body">
            <form action="ProductoController.php?accion=cotizar" method="POST" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Producto a Cotizar</label>
                    <select name="id_producto" class="form-select" required>
                        <option value="">-- Seleccione un ítem del catálogo --</option>
                        <?php foreach ($productos as $p): ?>
                            <option value="<?= $p['id'] ?>">
                                <?= htmlspecialchars($p['nombre_producto']) ?> (SKU: <?= htmlspecialchars($p['codigo_sku']) ?>) - $<?= number_format($p['precio_venta'], 2) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Cantidad</label>
                    <input type="number" name="cantidad" value="1" min="1" class="form-control" required>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-info text-white w-100 fw-bold">Generar Cotización</button>
                </div>
            </form>

            <?php if (isset($cotizacion) && $cotizacion): ?>
                <div class="mt-4 p-3 bg-white border rounded shadow-sm">
                    <h5 class="text-primary border-bottom pb-2">Resultado de la Cotización Comercial</h5>
                    <div class="row text-center">
                        <div class="col-md-2">
                            <small class="text-muted">Subtotal Bruto</small><br>
                            <strong>$<?= number_format($cotizacion['subtotal_bruto'], 2) ?></strong>
                        </div>
                        <div class="col-md-2 text-danger">
                            <small class="text-muted">Desc. Volumen (5%)</small><br>
                            <strong>-$<?= number_format($cotizacion['descuento'], 2) ?></strong>
                        </div>
                        <div class="col-md-2">
                            <small class="text-muted">Subtotal Neto</small><br>
                            <strong>$<?= number_format($cotizacion['subtotal_neto'], 2) ?></strong>
                        </div>
                        <div class="col-md-3 text-secondary">
                            <small class="text-muted">IVA (13% El Salvador)</small><br>
                            <strong>+$<?= number_format($cotizacion['iva'], 2) ?></strong>
                        </div>
                        <div class="col-md-3 bg-success-subtle p-2 rounded">
                            <small class="text-success fw-bold">TOTAL A PAGAR</small><br>
                            <h4 class="text-success mb-0">$<?= number_format($cotizacion['total'], 2) ?></h4>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- TABLA DE MANTENIMIENTO CRUD -->
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white fw-bold">Catálogo General de Productos</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-secondary">
                        <tr>
                            <th>SKU</th>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Precio Base</th>
                            <th>IVA (13%)</th>
                            <th>Precio Venta</th>
                            <th>Stock</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($productos) > 0): ?>
                            <?php foreach ($productos as $item): ?>
                                <tr>
                                    <td><code><?= htmlspecialchars($item['codigo_sku']) ?></code></td>
                                    <td><strong><?= htmlspecialchars($item['nombre_producto']) ?></strong></td>
                                    <td><span class="badge bg-secondary"><?= htmlspecialchars($item['categoria']) ?></span></td>
                                    <td>$<?= number_format($item['precio_base'], 2) ?></td>
                                    <td>$<?= number_format($item['monto_iva'], 2) ?></td>
                                    <td class="text-success fw-bold">$<?= number_format($item['precio_venta'], 2) ?></td>
                                    <td>
                                        <span class="badge <?= $item['stock_actual'] <= 5 ? 'bg-danger' : 'bg-success' ?>">
                                            <?= $item['stock_actual'] ?> unidades
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="ProductoController.php?accion=editar&id=<?= $item['id'] ?>" class="btn btn-sm btn-outline-warning">Editar</a>
                                        <a href="ProductoController.php?accion=eliminar&id=<?= $item['id'] ?>" 
                                           class="btn btn-sm btn-outline-danger" 
                                           onclick="return confirm('¿Confirma la eliminación del producto <?= htmlspecialchars($item['codigo_sku']) ?>?');">Eliminar</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">No existen registros guardados en la base de datos.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>