import { pool } from "../db.js";

export const getCoreData = async (req, res) => {
    try {
        const [rows] = await pool.query("SELECT 1 + 1 AS result");
        res.json({ message: "¡Conexión exitosa!", result: rows[0] });
    } catch (error) {
        console.error("Error al obtener datos:", error.message);
        res.status(500).json({ error: "Error al obtener datos" });
    }
};
