import express from 'express';
import pool from '../config/database.js';

const router = express.Router();

// Get all students
router.get('/', async (req, res) => {
  try {
    const connection = await pool.getConnection();
    const [students] = await connection.query('SELECT * FROM students ORDER BY full_name');
    connection.release();
    res.json(students);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
});

// Get student by ID
router.get('/:id', async (req, res) => {
  try {
    const { id } = req.params;
    const connection = await pool.getConnection();
    const [students] = await connection.query('SELECT * FROM students WHERE id = ?', [id]);
    connection.release();
    
    if (students.length === 0) {
      return res.status(404).json({ error: 'Student not found' });
    }
    res.json(students[0]);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
});

// Create new student
router.post('/', async (req, res) => {
  try {
    const {
      student_id, lrn, full_name, grade, section, sex, date_of_birth,
      guardian, contact_number, beneficiary, has_allergy, allergy_notes, remarks
    } = req.body;

    // Input validation
    if (!student_id || !full_name || !grade || !section) {
      return res.status(400).json({ error: 'Missing required fields: student_id, full_name, grade, section' });
    }

    const connection = await pool.getConnection();
    
    // Check if student_id already exists
    const [existing] = await connection.query('SELECT id FROM students WHERE student_id = ?', [student_id]);
    if (existing.length > 0) {
      connection.release();
      return res.status(400).json({ error: 'Student ID already exists' });
    }

    const [result] = await connection.query(
      `INSERT INTO students (student_id, lrn, full_name, grade, section, sex, date_of_birth, 
       guardian, contact_number, beneficiary, has_allergy, allergy_notes, remarks, created_at, updated_at) 
       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())`,
      [student_id, lrn, full_name, grade, section, sex, date_of_birth, guardian, contact_number, beneficiary, has_allergy, allergy_notes, remarks]
    );
    connection.release();

    console.log(`New student created: ${full_name} (${student_id})`);

    res.status(201).json({
      id: result.insertId,
      student_id,
      full_name
    });
  } catch (error) {
    console.error('Error creating student:', error);
    res.status(500).json({ error: error.message });
  }
});

// Update student
router.put('/:id', async (req, res) => {
  try {
    const { id } = req.params;
    const {
      student_id, lrn, full_name, grade, section, sex, date_of_birth,
      guardian, contact_number, beneficiary, has_allergy, allergy_notes, remarks
    } = req.body;

    const connection = await pool.getConnection();
    await connection.query(
      `UPDATE students SET student_id = ?, lrn = ?, full_name = ?, grade = ?, section = ?, sex = ?, 
       date_of_birth = ?, guardian = ?, contact_number = ?, beneficiary = ?, has_allergy = ?, 
       allergy_notes = ?, remarks = ?, updated_at = NOW() WHERE id = ?`,
      [student_id, lrn, full_name, grade, section, sex, date_of_birth, guardian, contact_number, beneficiary, has_allergy, allergy_notes, remarks, id]
    );
    connection.release();

    res.json({ message: 'Student updated successfully' });
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
});

// Delete student
router.delete('/:id', async (req, res) => {
  try {
    const { id } = req.params;
    const connection = await pool.getConnection();
    const [result] = await connection.query('DELETE FROM students WHERE id = ?', [id]);
    connection.release();

    if (result.affectedRows === 0) {
      return res.status(404).json({ error: 'Student not found' });
    }
    res.json({ message: 'Student deleted successfully' });
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
});

export default router;
