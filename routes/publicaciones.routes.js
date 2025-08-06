import { Router } from "express";
const router = Router();

router.get("/", (req, res) => {
  res.json({ mensaje: "Esta es la ruta [get] de mi entidad [publicaciones]" });
});

router.get("/:id", (req, res) => {
  const id = req.params.id;
  res.json({ mensaje: `Esta es la ruta GET de mi entidad [publicaciones] con el ID ${id}` });
});

router.post("/", (req, res) => {
  res.json({ mensaje: "Esta es la ruta [post] de mi entidad [publicaciones]" });
});

router.put("/:id", (req, res) => {
  const id = req.params.id;
  res.json({ mensaje: `Esta es la ruta [put] de mi entidad [publicaciones] con el ID ${id}` });
});

router.delete("/:id", (req, res) => {
  const id = req.params.id;
  res.json({ mensaje: `Esta es la ruta [delete] de mi entidad [publicaciones] con el ID ${id}` });
});

export default router;
