import express from 'express';
import pool from '../config/database.js';

const router = express.Router();

// Get all measurements
router.get('/', async (req, res) => {
  try {
    const connection = await pool.getConnection();
    const [measurements] = await connection.query('SELECT * FROM measurements ORDER BY date DESC');
    connection.release();
    res.json(measurements);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
});

// Get measurements by student ID
router.get('/student/:student_id', async (req, res) => {
  try {
    const { student_id } = req.params;
    const connection = await pool.getConnection();
    const [measurements] = await connection.query(
      'SELECT * FROM measurements WHERE student_id = ? ORDER BY date DESC',
      [student_id]
    );
    connection.release();
    res.json(measurements);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
});

// Get measurement by ID
router.get('/:id', async (req, res) => {
  try {
    const { id } = req.params;
    const connection = await pool.getConnection();
    const [measurements] = await connection.query('SELECT * FROM measurements WHERE id = ?', [id]);
    connection.release();
    
    if (measurements.length === 0) {
      return res.status(404).json({ error: 'Measurement not found' });
    }
    res.json(measurements[0]);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
});

// Create new measurement
router.post('/', async (req, res) => {
  try {
    const {
      student_id, student_name, measurement_type, date, weight, height, bmi, nutritional_status, measured_by, remarks
    } = req.body;

    // Input validation
    if (!student_id || !date || weight === undefined || height === undefined) {
      return res.status(400).json({ error: 'Missing required fields: student_id, date, weight, height' });
    }

    // Validate numeric values
    if (isNaN(weight) || isNaN(height) || weight <= 0 || height <= 0) {
      return res.status(400).json({ error: 'Weight and height must be positive numbers' });
    }

    const connection = await pool.getConnection();
    const [result] = await connection.query(
      `INSERT INTO measurements (student_id, student_name, measurement_type, date, weight, height, bmi, 
       nutritional_status, measured_by, remarks, created_at, updated_at) 
       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())`,
      [student_id, student_name, measurement_type, date, weight, height, bmi, nutritional_status, measured_by, remarks]
    );
    connection.release();

    console.log(`New measurement created for student ${student_id}`);

    res.status(201).json({
      id: result.insertId,
      student_id,
      date,
      bmi,
      nutritional_status
    });
  } catch (error) {
    console.error('Error creating measurement:', error);
    res.status(500).json({ error: error.message });
  }
});

// Update measurement
router.put('/:id', async (req, res) => {
  try {
    const { id } = req.params;
    const {
      student_id, student_name, measurement_type, date, weight, height, bmi, nutritional_status, measured_by, remarks
    } = req.body;

    const connection = await pool.getConnection();
    await connection.query(
      `UPDATE measurements SET student_id = ?, student_name = ?, measurement_type = ?, date = ?, 
       weight = ?, height = ?, bmi = ?, nutritional_status = ?, measured_by = ?, remarks = ?, updated_at = NOW() WHERE id = ?`,
      [student_id, student_name, measurement_type, date, weight, height, bmi, nutritional_status, measured_by, remarks, id]
    );
    connection.release();

    res.json({ message: 'Measurement updated successfully' });
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
});

// Delete measurement
router.delete('/:id', async (req, res) => {
  try {
    const { id } = req.params;
    const connection = await pool.getConnection();
    const [result] = await connection.query('DELETE FROM measurements WHERE id = ?', [id]);
    connection.release();

    if (result.affectedRows === 0) {
      return res.status(404).json({ error: 'Measurement not found' });
    }
    res.json({ message: 'Measurement deleted successfully' });
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
});

export default router;
