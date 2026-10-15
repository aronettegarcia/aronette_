<?php
/**
 * Capa de Datos: ProductoModel
 */

require_once __DIR__ . '/../config/conexion.php';

class ProductoModel {
    private PDO $db;

    public function __construct(PDO $conexion) {
        $this->db = $conexion;
    }

    public function listarTodos(): array {
        $sql = "SELECT * FROM productos ORDER BY id DESC";
        return $this->db->query($sql)->fetchAll();
    }

    public function obtenerPorId(int $id): ?array {
        $sql = "SELECT * FROM productos WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    public function crear(string $sku, string $nombre, string $categoria, float $precioBase, int $stock): bool {
        $montoIva   = round($precioBase * 0.13, 2);
        $precioVenta = round($precioBase + $montoIva, 2);

        $sql = "INSERT INTO productos (codigo_sku, nombre_producto, categoria, precio_base, monto_iva, precio_venta, stock_actual) 
                VALUES (:sku, :nombre, :categoria, :precio_base, :monto_iva, :precio_venta, :stock)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':sku'          => $sku,
            ':nombre'       => $nombre,
            ':categoria'    => $categoria,
            ':precio_base'  => $precioBase,
            ':monto_iva'    => $montoIva,
            ':precio_venta' => $precioVenta,
            ':stock'        => $stock
        ]);
    }

    public function actualizar(int $id, string $sku, string $nombre, string $categoria, float $precioBase, int $stock): bool {
        $montoIva   = round($precioBase * 0.13, 2);
        $precioVenta = round($precioBase + $montoIva, 2);

        $sql = "UPDATE productos 
                SET codigo_sku = :sku, 
                    nombre_producto = :nombre, 
                    categoria = :categoria, 
                    precio_base = :precio_base, 
                    monto_iva = :monto_iva, 
                    precio_venta = :precio_venta, 
                    stock_actual = :stock 
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':sku'          => $sku,
            ':nombre'       => $nombre,
            ':categoria'    => $categoria,
            ':precio_base'  => $precioBase,
            ':monto_iva'    => $montoIva,
            ':precio_venta' => $precioVenta,
            ':stock'        => $stock,
            ':id'           => $id
        ]);
    }

    public function eliminar(int $id): bool {
        $sql = "DELETE FROM productos WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}