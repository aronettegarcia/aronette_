-- =============================================================================
-- PROYECTO INTEGRADOR: PYME
-- ESTÁNDAR TÉCNICO DE BASE DE DATOS (MySQL 8.0 / InnoDB)
-- =============================================================================

CREATE DATABASE IF NOT EXISTS pyme_db 
    CHARACTER SET utf8mb4 
    COLLATE utf8mb4_unicode_ci;

USE pyme_db;

-- 1. TABLA: clientes (Módulo 3: Mini CRM)
CREATE TABLE IF NOT EXISTS clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    dui_nit VARCHAR(20) NOT NULL UNIQUE,
    nombre_completo VARCHAR(150) NOT NULL,
    telefono VARCHAR(15) NULL,
    correo VARCHAR(100) NULL,
    direccion TEXT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. TABLA: proveedores (Módulo 3: Mini CRM)
CREATE TABLE IF NOT EXISTS proveedores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nrc VARCHAR(20) NOT NULL UNIQUE,
    nombre_comercial VARCHAR(150) NOT NULL,
    contacto_principal VARCHAR(100) NULL,
    telefono VARCHAR(15) NULL,
    correo VARCHAR(100) NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. TABLA: productos (Módulo 1 y 2: Catálogo e Inventario)
CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo_sku VARCHAR(30) NOT NULL UNIQUE,
    nombre_producto VARCHAR(150) NOT NULL,
    categoria VARCHAR(50) DEFAULT 'General',
    precio_base DECIMAL(10,2) NOT NULL,  -- Precio Neto sin impuesto
    monto_iva DECIMAL(10,2) NOT NULL,    -- 13% de IVA calculado
    precio_venta DECIMAL(10,2) NOT NULL, -- Precio Final (Base + IVA)
    stock_actual INT NOT NULL DEFAULT 0,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. TABLA: facturas (Módulo 4: Facturación)
CREATE TABLE IF NOT EXISTS facturas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero_factura VARCHAR(30) NOT NULL UNIQUE,
    cliente_id INT NOT NULL,
    fecha_emision DATETIME DEFAULT CURRENT_TIMESTAMP,
    subtotal_neto DECIMAL(10,2) NOT NULL,
    monto_iva DECIMAL(10,2) NOT NULL,
    total_pagar DECIMAL(10,2) NOT NULL,
    estado ENUM('Pendiente', 'Pagada', 'Anulada') DEFAULT 'Pagada',
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. TABLA: detalles_factura (Relación Maestro-Detalle)
CREATE TABLE IF NOT EXISTS detalles_factura (
    id INT AUTO_INCREMENT PRIMARY KEY,
    factura_id INT NOT NULL,
    producto_id INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (factura_id) REFERENCES facturas(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- SEMILLA DE DATOS INICIALES PARA EL DESARROLLO
INSERT INTO productos (codigo_sku, nombre_producto, categoria, precio_base, monto_iva, precio_venta, stock_actual) VALUES
('FER-101', 'Martillo de Uña 16oz Stanley', 'Herramientas', 10.00, 1.30, 11.30, 25),
('FER-102', 'Pintura Látex Blanco 1 Galón', 'Pinturas', 18.00, 2.34, 20.34, 12),
('FER-103', 'Caja de Clavos 2 Pulgadas (1lb)', 'Fijaciones', 2.50, 0.33, 2.83, 50);