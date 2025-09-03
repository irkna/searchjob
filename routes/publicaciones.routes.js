import { Router } from "express";
import prisma from "../lib/prisma.js";
import logger from "../middlewares/logger.js";

const router = Router();

router.use(logger); 

router.get("/productos", async (req, res) => {
  try {
    const productos = await prisma.productos.findMany();
    res.json(productos);
  } catch (error) {
    res.status(500).json({ error: "Hubo un error al traer los productos" });
  }
});

router.get("/productos/:id", async (req, res) => {
  try {
    const id = parseInt(req.params.id);
    const producto = await prisma.productos.findUnique({
      where: { id: id },
    });
    res.json(producto);
  } catch (error) {
    res.status(500).json({ error: "Hubo un error al traer el producto" });
  }
});

router.post("/productos", async (req, res) => {
  try {
    const { nombre, descripcion, img } = req.body;
    const nuevoProducto = await prisma.productos.create({
      data: {
        nombre,
        descripcion,
        img,
      },
    });
    res.json(nuevoProducto);
  } catch (error) {
    res.status(500).json({ error: "Hubo un error al crear el producto" });
  }
});

router.put("/productos/:id", async (req, res) => {
  try {
    const id = parseInt(req.params.id);
    const { nombre, descripcion, img } = req.body;
    const productoActualizado = await prisma.productos.update({
      where: { id: id },
      data: {
        nombre,
        descripcion,
        img,
      },
    });
    res.json(productoActualizado);
  } catch (error) {
    res.status(500).json({ error: "Hubo un error al actualizar el producto" });
  }
});

router.delete("/productos/:id", async (req, res) => {
  try {
    const id = parseInt(req.params.id);
    await prisma.productos.delete({
      where: { id: id },
    });
    res.json({ mensaje: "Producto eliminado" });
  } catch (error) {
    res.status(500).json({ error: "Hubo un error al eliminar el producto" });
  }
});

export default router;
