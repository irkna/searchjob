import { Router } from "express";
const router = Router();

router.get("/productos", (req, res) => {
  res.json({ mensaje: "Esta es la ruta [get] de mi entidad [publicaciones]" });
});

router.get("/productos/:id", (req, res) => {
  const id = req.params.id;
  res.json({ mensaje: `Esta es la ruta GET de mi entidad [publicaciones] con el ID ${id}` });
});

router.post("/productos", (req, res) => {
  res.json({ mensaje: "Esta es la ruta [post] de mi entidad [publicaciones]" });
});

router.put("/productos/:id", (req, res) => {
  const id = req.params.id;
  res.json({ mensaje: `Esta es la ruta [put] de mi entidad [publicaciones] con el ID ${id}` });
});

router.delete("/productos/:id", (req, res) => {
  const id = req.params.id;
  res.json({ mensaje: `Esta es la ruta [delete] de mi entidad [publicaciones] con el ID ${id}` });
});

export default router;

