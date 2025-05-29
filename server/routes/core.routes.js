import { Router } from "express";
import { getCoreData } from "../controllers/core.controller.js";
import { pool } from "../db.js";

const router = Router();

router.get("/coredata", getCoreData); // ✅

router.get("/ping", async (req, res) => {
    try {
        const [rows] = await pool.query("SELECT 1 + 1 AS result");
        console.log(rows);
        res.json(rows);
    } catch (error) {
        console.error("Error al realizar consulta:", error.message);
        res.status(500).json({ error: "Error en la conexión a la base de datos" });
    }
});

export default router;
