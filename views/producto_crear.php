<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>PYME - Registrar Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container my-5" style="max-width: 600px;">
    <div class="card shadow">
        <div class="card-header bg-primary text-white fw-bold">Registrar Nuevo Producto</div>
        <div class="card-body">
            <form action="ProductoController.php?accion=guardar" method="POST">
                <div class="mb-3">
                    <label class="form-label">Código SKU</label>
                    <input type="text" name="codigo_sku" class="form-control" placeholder="Ej: FER-1004" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nombre del Producto</label>
                    <input type="text" name="nombre_producto" class="form-control" placeholder="Ej: Disco de Corte 4 1/2 Dewalt" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Categoría</label>
                    <input type="text" name="categoria" class="form-control" placeholder="Ej: Accesorios de Construcción">
                </div>
                <div class="mb-3">
                    <label class="form-label">Precio Base (Sin IVA)</label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" step="0.01" name="precio_base" class="form-control" placeholder="0.00" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Stock Inicial</label>
                    <input type="number" name="stock_actual" class="form-control" value="10" required>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="ProductoController.php" class="btn btn-secondary">Regresar</a>
                    <button type="submit" class="btn btn-success">Guardar Producto</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>