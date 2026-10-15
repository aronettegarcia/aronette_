<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>PYME - Editar Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container my-5" style="max-width: 600px;">
    <div class="card shadow">
        <div class="card-header bg-warning text-dark fw-bold">Modificar Producto Existente</div>
        <div class="card-body">
            <form action="ProductoController.php?accion=actualizar" method="POST">
                <input type="hidden" name="id" value="<?= $producto['id'] ?>">

                <div class="mb-3">
                    <label class="form-label">Código SKU</label>
                    <input type="text" name="codigo_sku" class="form-control" value="<?= htmlspecialchars($producto['codigo_sku']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nombre del Producto</label>
                    <input type="text" name="nombre_producto" class="form-control" value="<?= htmlspecialchars($producto['nombre_producto']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Categoría</label>
                    <input type="text" name="categoria" class="form-control" value="<?= htmlspecialchars($producto['categoria']) ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Precio Base (Sin IVA)</label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" step="0.01" name="precio_base" class="form-control" value="<?= $producto['precio_base'] ?>" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Stock Actual</label>
                    <input type="number" name="stock_actual" class="form-control" value="<?= $producto['stock_actual'] ?>" required>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="ProductoController.php" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-warning">Actualizar Registro</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>