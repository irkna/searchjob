import { Router } from "express";
import prisma from "../lib/prisma.js";
import logger from "../middlewares/logger.js";

const router = Router();

router.use(logger); 

router.get("/usuarios", async (req, res) => {
  try {
    const usuarios = await prisma.usuarios.findMany();
    res.json(usuarios);
  } catch (error) {
    res.status(500).json({ error: "Hubo un error al traer los usuarios" });
  }
});

router.get("/usuarios/:id", async (req, res) => {
  try {
    const id = parseInt(req.params.id);
    const usuario = await prisma.usuarios.findUnique({
      where: { id: id },
    });
    res.json(usuario);
  } catch (error) {
    res.status(500).json({ error: "Hubo un error al traer el usuario" });
  }
});

router.post("/usuarios", async (req, res) => {
  try {
    const { nombre, apellido, dni } = req.body;
    const nuevoUsuario = await prisma.usuarios.create({
      data: {
        nombre,
        apellido,
        dni,
      },
    });
    res.json(nuevoUsuario);
  } catch (error) {
    res.status(500).json({ error: "Hubo un error al crear el usuario" });
  }
});

router.put("/usuarios/:id", async (req, res) => {
  try {
    const id = parseInt(req.params.id);
    const { nombre, apellido, dni } = req.body;
    const usuarioActualizado = await prisma.usuarios.update({
      where: { id: id },
      data: {
        nombre,
        apellido,
        dni,
      },
    });
    res.json(usuarioActualizado);
  } catch (error) {
    res.status(500).json({ error: "Hubo un error al actualizar el usuario" });
  }
});

router.delete("/usuarios/:id", async (req, res) => {
  try {
    const id = parseInt(req.params.id);
    await prisma.usuarios.delete({
      where: { id: id },
    });
    res.json({ mensaje: "Usuario eliminado" });
  } catch (error) {
    res.status(500).json({ error: "Hubo un error al eliminar el usuario" });
  }
});

export default router;
