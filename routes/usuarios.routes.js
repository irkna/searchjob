import { Router } from "express";
const router = Router();

router.get("/usuarios", (req, res) => {
  res.json({ mensaje: "Esta es la ruta [get] de mi entidad [usuarios]" });
});

router.get("/usuarios/:id", (req, res) => {
  const id = req.params.id;
  res.json({ mensaje: `Esta es la ruta GET de mi entidad [usuarios] con el ID ${id}` });
});

router.post("/usuarios", (req, res) => {
  res.json({ mensaje: "Esta es la ruta [post] de mi entidad [usuarios]" });
});

router.put("/usuarios/:id", (req, res) => {
  const id = req.params.id;
  res.json({ mensaje: `Esta es la ruta [put] de mi entidad [usuarios] con el ID ${id}` });
});

router.delete("/usuarios/:id", (req, res) => {
  const id = req.params.id;
  res.json({ mensaje: `Esta es la ruta [delete] de mi entidad [usuarios] con el ID ${id}` });
});

export default router;

