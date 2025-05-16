CREATE DATABASE tienda_agropecuaria;

USE tienda_agropecuaria;

-- Tabla de productos
CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    categoria VARCHAR(50) NOT NULL,
    stock INT NOT NULL,
    imagen VARCHAR(100),
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('usuario','admin') NOT NULL DEFAULT 'usuario',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO usuarios (nombre, email, password, rol) 
VALUES (
    'Administrador Principal', 
    'admin@tudominio.com', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',  --  La Contraseña es : "password"
    'admin'
);

-- Insertar productos
INSERT INTO productos (nombre, descripcion, precio, categoria, stock, imagen) VALUES
('Manguera de riego', 'Manguera de riego 3/4 de 100 metros', 50, 'riego', 10,'Manguera.jpg'),
('Saco de Urea 50kg', 'Saco de urea 46% de nitrogeno, 50kg de contenido', 40, 'fertilizantes', 50,'Urea.jpg'),
('Alimento para pollos', 'Saco de alimento para pollos y engorde 40kg', 50, 'Alimentos', 30,'Alimento_pollos.jpg'),
('Machete para rozar', 'Machete rozador de 22 pulgadas nuevo', 25, 'Herramientas', 15,'Machete.jpg'),
('Semillas de Tomate', 'Semillas de tomate marca seminis, 5000 semillas.', 35, 'Semillas', 15,'Semillas_tomate.jpg');
('Insecticida karate', 'Karate Zeon + 1,5 cs Lambda cihalotrin 1,5% insecticida 4 litros', 35, 'Insecticidas', 15,'Insecticida_karate.jpg');

