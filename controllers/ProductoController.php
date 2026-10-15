<?php
/**
 * Controlador Principal: ProductoController
 */

require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../models/ProductoModel.php';

// Verificación de seguridad de la conexión
if (!isset($pdo) || !($pdo instanceof PDO)) {
    die("Error crítico: La variable \$pdo no está definida. Verifique config/conexion.php");
}

$model = new ProductoModel($pdo);

$accion    = $_GET['accion'] ?? 'listar';
$mensaje   = $_GET['mensaje'] ?? '';
$tipoAlert = $_GET['tipo'] ?? 'info';

switch ($accion) {

    case 'crear':
        require_once __DIR__ . '/../views/producto_crear.php';
        break;

    case 'guardar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $sku        = trim($_POST['codigo_sku'] ?? '');
            $nombre     = trim($_POST['nombre_producto'] ?? '');
            $categoria  = trim($_POST['categoria'] ?? 'General');
            $precioBase = filter_var($_POST['precio_base'] ?? 0, FILTER_VALIDATE_FLOAT);
            $stock      = filter_var($_POST['stock_actual'] ?? 0, FILTER_VALIDATE_INT);

            if (!empty($sku) && !empty($nombre) && $precioBase > 0 && $stock >= 0) {
                try {
                    $model->crear($sku, $nombre, $categoria, $precioBase, $stock);
                    header("Location: ProductoController.php?accion=listar&mensaje=Producto+registrado+con+exito&tipo=success");
                    exit;
                } catch (\PDOException $e) {
                    $msgError = ($e->getCode() == 23000) ? "El+codigo+SKU+ya+se+encuentra+registrado" : "Error+al+guardar+producto";
                    header("Location: ProductoController.php?accion=crear&mensaje=$msgError&tipo=danger");
                    exit;
                }
            }
        }
        header("Location: ProductoController.php?accion=crear&mensaje=Complete+los+campos+obligatorios&tipo=warning");
        exit;

    case 'editar':
        $id = filter_var($_GET['id'] ?? 0, FILTER_VALIDATE_INT);
        $producto = $model->obtenerPorId($id);

        if (!$producto) {
            header("Location: ProductoController.php?accion=listar&mensaje=Producto+no+encontrado&tipo=danger");
            exit;
        }
        require_once __DIR__ . '/../views/producto_editar.php';
        break;

    case 'actualizar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id         = filter_var($_POST['id'] ?? 0, FILTER_VALIDATE_INT);
            $sku        = trim($_POST['codigo_sku'] ?? '');
            $nombre     = trim($_POST['nombre_producto'] ?? '');
            $categoria  = trim($_POST['categoria'] ?? '');
            $precioBase = filter_var($_POST['precio_base'] ?? 0, FILTER_VALIDATE_FLOAT);
            $stock      = filter_var($_POST['stock_actual'] ?? 0, FILTER_VALIDATE_INT);

            if ($id && !empty($sku) && !empty($nombre) && $precioBase > 0 && $stock >= 0) {
                $model->actualizar($id, $sku, $nombre, $categoria, $precioBase, $stock);
                header("Location: ProductoController.php?accion=listar&mensaje=Producto+actualizado+correctamente&tipo=success");
                exit;
            }
        }
        header("Location: ProductoController.php?accion=listar&mensaje=Error+al+actualizar+los+datos&tipo=danger");
        exit;

    case 'eliminar':
        $id = filter_var($_GET['id'] ?? 0, FILTER_VALIDATE_INT);
        if ($id && $model->eliminar($id)) {
            header("Location: ProductoController.php?accion=listar&mensaje=Producto+eliminado+exitosamente&tipo=success");
            exit;
        }
        header("Location: ProductoController.php?accion=listar&mensaje=No+se+pudo+eliminar+el+registro&tipo=danger");
        exit;

    case 'cotizar':
        $idProducto = filter_var($_POST['id_producto'] ?? 0, FILTER_VALIDATE_INT);
        $cantidad   = filter_var($_POST['cantidad'] ?? 1, FILTER_VALIDATE_INT);

        $producto   = $model->obtenerPorId($idProducto);
        $cotizacion = null;

        if ($producto && $cantidad > 0) {
            $subtotalBruto = $producto['precio_base'] * $cantidad;
            $porcentajeDesc = ($subtotalBruto >= 100.00) ? 0.05 : 0.00;
            $montoDescuento = $subtotalBruto * $porcentajeDesc;
            $subtotalNeto   = $subtotalBruto - $montoDescuento;
            $montoIva       = $subtotalNeto * 0.13;
            $totalPagar     = $subtotalNeto + $montoIva;

            $cotizacion = [
                'producto'        => $producto['nombre_producto'],
                'sku'             => $producto['codigo_sku'],
                'cantidad'        => $cantidad,
                'precio_unitario' => $producto['precio_base'],
                'subtotal_bruto'  => $subtotalBruto,
                'descuento'       => $montoDescuento,
                'subtotal_neto'   => $subtotalNeto,
                'iva'             => $montoIva,
                'total'           => $totalPagar
            ];
        }
        $productos = $model->listarTodos();
        require_once __DIR__ . '/../views/producto_listado.php';
        break;

    default:
        $productos = $model->listarTodos();
        require_once __DIR__ . '/../views/producto_listado.php';
        break;
}