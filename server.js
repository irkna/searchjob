console.log(" 7°2 actividad numero 10");

import express from "express";

import publicacionesRoutes from "./routes/publicaciones.routes.js";
import usuariosRoutes from "./routes/usuarios.routes.js";

const servidor = express();

servidor.use(express.json());

// Rutas
servidor.use("/publicaciones", publicacionesRoutes);
servidor.use("/usuarios", usuariosRoutes);

servidor.get("/publicacion", (req, res) => {
  res.json({
    mensaje: "Bienvenido a la API de [searchjob]",
  });
});

servidor.listen(3000, () => {
  console.log("El servidor express está en el puerto 3000");
});

