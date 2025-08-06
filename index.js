console.log(" 7°2");

import express from "express"

const servidor=express()
servidor.get("/publicacion",(req,res)=>{
    res.json({
        mensaje:"Bienvenido a la API de [searchjob]"
    });
});

servidor.get("/publicaciones",(req,res)=>{
    res.json({
        mensaje:"Esta es la ruta [get] de mi entidad [publicaciones]"
    });
});

servidor.post("/publicaciones",(req,res)=>{
    res.json({
        mensaje:"Esta es la ruta [post] de mi entidad [publicaciones]"
    });
});
servidor.put("/publicaciones",(req,res)=>{
res.json({
        mensaje:"Esta es la ruta [put] de mi entidad [publicaciones]"
    });
});
servidor.delete("/publicaciones",(req,res)=>{
res.json({
        mensaje:"Esta es la ruta [delete] de mi entidad [publicaciones]"
    });
});

servidor.listen(3000,()=>{
    console.log("el servidor express esta en el puerto 3000");
})
