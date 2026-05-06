import express from 'express';
import pool from '../config/database.js';

const router = express.Router();

// Get all attendance records
router.get('/', async (req, res) => {
  try {
    const connection = await pool.getConnection();
    const [attendance] = await connection.query('SELECT * FROM attendance ORDER BY date DESC');
    connection.release();
    res.json(attendance);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
});

// Get attendance by student ID
router.get('/student/:student_id', async (req, res) => {
  try {
    const { student_id } = req.params;
    const connection = await pool.getConnection();
    const [attendance] = await connection.query(
      'SELECT * FROM attendance WHERE student_id = ? ORDER BY date DESC',
      [student_id]
    );
    connection.release();
    res.json(attendance);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
});

// Get attendance record by ID
router.get('/:id', async (req, res) => {
  try {
    const { id } = req.params;
    const connection = await pool.getConnection();
    const [attendance] = await connection.query('SELECT * FROM attendance WHERE id = ?', [id]);
    connection.release();
    
    if (attendance.length === 0) {
      return res.status(404).json({ error: 'Attendance record not found' });
    }
    res.json(attendance[0]);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
});

// Create new attendance record
router.post('/', async (req, res) => {
  try {
    const { student_id, date, present, meal_received, remarks } = req.body;

    const connection = await pool.getConnection();
    
    // Check if attendance record already exists
    const [existing] = await connection.query(
      'SELECT id FROM attendance WHERE student_id = ? AND date = ?',
      [student_id, date]
    );
    if (existing.length > 0) {
      connection.release();
      return res.status(400).json({ error: 'Attendance record already exists for this date' });
    }

    const [result] = await connection.query(
      `INSERT INTO attendance (student_id, date, present, meal_received, remarks, created_at, updated_at) 
       VALUES (?, ?, ?, ?, ?, NOW(), NOW())`,
      [student_id, date, present, meal_received, remarks]
    );
    connection.release();

    res.status(201).json({
      id: result.insertId,
      student_id,
      date,
      present,
      meal_received
    });
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
});

// Update attendance record
router.put('/:id', async (req, res) => {
  try {
    const { id } = req.params;
    const { student_id, date, present, meal_received, remarks } = req.body;

    const connection = await pool.getConnection();
    await connection.query(
      `UPDATE attendance SET student_id = ?, date = ?, present = ?, meal_received = ?, remarks = ?, updated_at = NOW() 
       WHERE id = ?`,
      [student_id, date, present, meal_received, remarks, id]
    );
    connection.release();

    res.json({ message: 'Attendance record updated successfully' });
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
});

// Delete attendance record
router.delete('/:id', async (req, res) => {
  try {
    const { id } = req.params;
    const connection = await pool.getConnection();
    const [result] = await connection.query('DELETE FROM attendance WHERE id = ?', [id]);
    connection.release();

    if (result.affectedRows === 0) {
      return res.status(404).json({ error: 'Attendance record not found' });
    }
    res.json({ message: 'Attendance record deleted successfully' });
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
});

export default router;
