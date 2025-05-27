import { createPool } from "mysql2/promise";
import dotenv from "dotenv";

dotenv.config();

export const pool = createPool({
    host: process.env.DB_HOST,
    port: process.env.DB_PORT || 3306, 
    user: process.env.DB_USER,
    password: process.env.DB_PASSWORD,
    database: process.env.DB_NAME,
});

pool.getConnection()
    .then(() => console.log("Conexión exitosa con la base de datos"))
    .catch((err) => console.error("Error al conectar con la base de datos:", err));
