import { pool } from "../db.js";

// Obtener todos los usuarios
export const getUsers = async (req, res) => {
  try {
    const [rows] = await pool.query("SELECT id, nombre, correo, rol, area FROM usuarios");
    res.json(rows);
  } catch (error) {
    console.error("Error al obtener usuarios:", error.message);
    res.status(500).json({ error: "Error interno del servidor" });
  }
};

// Obtener un usuario por su ID
export const getUser = async (req, res) => {
  const { id } = req.params;
  try {
    const [rows] = await pool.query("SELECT id, nombre, correo, rol, area FROM usuarios WHERE id = ?", [id]);
    if (rows.length === 0) {
      return res.status(404).json({ message: "Usuario no encontrado" });
    }
    res.json(rows[0]);
  } catch (error) {
    console.error("Error al obtener usuario:", error.message);
    res.status(500).json({ error: "Error interno del servidor" });
  }
};

// Crear nuevo usuario (rol por defecto: usuario)
export const createUser = async (req, res) => {
  const { nombre, correo, contraseña, area } = req.body;
  try {
    const rol = "usuario";
    const [result] = await pool.query(
      "INSERT INTO usuarios (nombre, correo, contraseña, rol, area) VALUES (?, ?, ?, ?, ?)",
      [nombre, correo, contraseña, rol, area]
    );
    res.status(201).json({ message: "Usuario creado exitosamente", id: result.insertId });
  } catch (error) {
    console.error("Error al crear usuario:", error.message);
    res.status(500).json({ error: "No se pudo crear el usuario" });
  }
};

// Actualizar información de un usuario
export const updateUser = async (req, res) => {
  const { id } = req.params;
  const { nombre, correo, contraseña, rol, area } = req.body;
  try {
    const [result] = await pool.query(
      `UPDATE usuarios 
       SET nombre = ?, correo = ?, contraseña = ?, rol = ?, area = ?
       WHERE id = ?`,
      [nombre, correo, contraseña, rol, area, id]
    );
    if (result.affectedRows === 0) {
      return res.status(404).json({ message: "Usuario no encontrado" });
    }
    res.json({ message: "Usuario actualizado correctamente" });
  } catch (error) {
    console.error("Error al actualizar usuario:", error.message);
    res.status(500).json({ error: "No se pudo actualizar el usuario" });
  }
};

// Eliminar un usuario
export const deleteUser = async (req, res) => {
  const { id } = req.params;
  try {
    const [result] = await pool.query("DELETE FROM usuarios WHERE id = ?", [id]);
    if (result.affectedRows === 0) {
      return res.status(404).json({ message: "Usuario no encontrado" });
    }
    res.json({ message: "Usuario eliminado correctamente" });
  } catch (error) {
    console.error("Error al eliminar usuario:", error.message);
    res.status(500).json({ error: "No se pudo eliminar el usuario" });
  }
};
