
CREATE DATABASE IF NOT EXISTS citasmedicas;
USE citasmedicas;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    usuario VARCHAR(100),
    correo VARCHAR(100) UNIQUE,
    contrasena VARCHAR(255),
    rol ENUM('admin', 'recepcionista') NOT NULL
);

CREATE TABLE citas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_paciente VARCHAR(100),
    fecha DATE,
    hora TIME,
    estado ENUM('pendiente', 'confirmada', 'cancelada') DEFAULT 'pendiente',
    nombre_usuario VARCHAR(100)
);

-- usuario: admin clave: 123456

INSERT INTO usuarios (nombre, correo, usuario, contrasena, rol)
VALUES ('Recepcionista', 'recepcionista@demo.com', 'recepcionista',
        '$2y$10$2O5vLHR.GEKQZFRgTAzpiebK0sIw2bZT4E5m4TP3wayqhOQGjhW5.', 'recepcionista');

INSERT INTO usuarios (nombre, correo, usuario, contrasena, rol)
VALUES ('Administrador General', 'admin@demo.com', 'admin',
        '$2y$10$2O5vLHR.GEKQZFRgTAzpiebK0sIw2bZT4E5m4TP3wayqhOQGjhW5.', 'admin');