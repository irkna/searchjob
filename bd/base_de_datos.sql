CREATE DATABASE searchjob;
USE searchjob;

CREATE TABLE `usuarios` (
  `dni` INT(8) NOT NULL PRIMARY KEY,
  `nombre` VARCHAR(100) NOT NULL,
  `telefono` INT(10) NOT NULL,
  `localidad` VARCHAR(100) NOT NULL,
  `gmail` VARCHAR(110) UNIQUE NOT NULL,
  `contrasena` VARCHAR(110)  NOT NULL,
  `foto_perfil` VARCHAR(255),
  `fecha` DATE
);


CREATE TABLE `trabajador` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `cuil` BIGINT NOT NULL UNIQUE,
  `matricula` VARCHAR(50) NOT NULL,
  `ocupacion` VARCHAR(100) NOT NULL,
  `linkdepago` VARCHAR(255) DEFAULT NULL,  
  `identificador` BIGINT NOT NULL,
  FOREIGN KEY (`identificador`) REFERENCES usuarios(`dni`)
);


CREATE TABLE `publicacion` (
  `id_publicacion` INT(10) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `foto_publicacion` VARCHAR(255),
  `descripcion` VARCHAR(100) NOT NULL,
  `dni_usuario` INT(8) NOT NULL,
  FOREIGN KEY (`dni_usuario`) REFERENCES usuarios(`dni`)
);

CREATE TABLE `comentario` (
  `id_comentario` INT AUTO_INCREMENT PRIMARY KEY,
  `id_publicacion` INT NOT NULL,
  `dni_usuario` INT NOT NULL,
  `comentario` TEXT NOT NULL,
  `fecha` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`id_publicacion`) REFERENCES publicacion(`id_publicacion`) ON DELETE CASCADE,
  FOREIGN KEY (`dni_usuario`) REFERENCES usuarios(`dni`) ON DELETE CASCADE
);

CREATE TABLE `notificaciones` (
  `id_notificacion` INT AUTO_INCREMENT PRIMARY KEY,
  `dni_usuario` INT NOT NULL,
  `tipo` ENUM('comentario','calificacion') NOT NULL,
  `mensaje` TEXT NOT NULL,
  `enlace` VARCHAR(255),
  `fecha` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `leido` TINYINT(1) DEFAULT 0,
  FOREIGN KEY (`dni_usuario`) REFERENCES usuarios(`dni`) ON DELETE CASCADE
);

CREATE TABLE `califica` (
  `id_calificacion` INT(10) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `dni_trabajador` INT(8) NOT NULL,
  `dni_usuario` INT(8) NOT NULL,
  `puntuacion` INT(5) NOT NULL,
  `comentario` TEXT(100) NOT NULL,
  FOREIGN KEY (`dni_trabajador`) REFERENCES trabajador(`identificador`),
  FOREIGN KEY (`dni_usuario`) REFERENCES usuarios(`dni`)
);

CREATE TABLE `contrato` (
  `id_servicio` INT(10) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `dni_trabajador` INT(8) NOT NULL,
  `dni_usuario` INT(8) NOT NULL,
  `costo` INT(100) NOT NULL,
  `metodo_de_pago` VARCHAR(50) NOT NULL,
  `fecha_y_hora` DATETIME NOT NULL,
  `descripcion` VARCHAR(100) NOT NULL,
  `ubicacion` VARCHAR(100) NOT NULL,
  FOREIGN KEY (`dni_trabajador`) REFERENCES trabajador(`identificador`),
  FOREIGN KEY (`dni_usuario`) REFERENCES usuarios(`dni`)
);

CREATE TABLE `mensajes` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `emisor_dni` int(20) NOT NULL,
  `receptor_dni` int(20) NOT NULL,
  `mensaje` TEXT NOT NULL,
  `fecha` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`receptor_dni`) REFERENCES trabajador(`identificador`),
  FOREIGN KEY (`emisor_dni`) REFERENCES usuarios(`dni`)
);

ALTER TABLE mensajes ADD COLUMN leido TINYINT(1) DEFAULT 0;





