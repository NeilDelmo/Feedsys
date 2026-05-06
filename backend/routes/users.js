import express from 'express';
import bcryptjs from 'bcryptjs';
import pool from '../config/database.js';

const router = express.Router();

// Get all users
router.get('/', async (req, res) => {
  try {
    const connection = await pool.getConnection();
    const [users] = await connection.query('SELECT id, username, full_name, role, email, is_active, created_at FROM users');
    connection.release();
    res.json(users);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
});

// Get user by ID
router.get('/:id', async (req, res) => {
  try {
    const { id } = req.params;
    const connection = await pool.getConnection();
    const [users] = await connection.query('SELECT id, username, full_name, role, email, is_active, created_at FROM users WHERE id = ?', [id]);
    connection.release();
    
    if (users.length === 0) {
      return res.status(404).json({ error: 'User not found' });
    }
    res.json(users[0]);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
});

// Create new user
router.post('/', async (req, res) => {
  try {
    const { username, password, full_name, role, email } = req.body;

    // Input validation
    if (!username || !password || !full_name || !role || !email) {
      return res.status(400).json({ error: 'Missing required fields' });
    }

    // Validate email format
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
      return res.status(400).json({ error: 'Invalid email format' });
    }

    // Validate password strength
    if (password.length < 6) {
      return res.status(400).json({ error: 'Password must be at least 6 characters long' });
    }

    if (username.length < 3) {
      return res.status(400).json({ error: 'Username must be at least 3 characters long' });
    }

    const connection = await pool.getConnection();
    
    // Check if username already exists
    const [existing] = await connection.query('SELECT id FROM users WHERE username = ?', [username]);
    if (existing.length > 0) {
      connection.release();
      return res.status(400).json({ error: 'Username already exists' });
    }

    // Hash password with bcrypt
    const saltRounds = 10;
    const hashedPassword = await bcryptjs.hash(password, saltRounds);
    
    const [result] = await connection.query(
      'INSERT INTO users (username, password, full_name, role, email, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, ?, 1, NOW(), NOW())',
      [username, hashedPassword, full_name, role, email]
    );
    connection.release();

    console.log(`New user created: ${username}`);

    res.status(201).json({
      id: result.insertId,
      username,
      full_name,
      role,
      email
    });
  } catch (error) {
    console.error('Error creating user:', error);
    res.status(500).json({ error: error.message });
  }
});

// Update user
router.put('/:id', async (req, res) => {
  try {
    const { id } = req.params;
    const { full_name, role, email, is_active } = req.body;

    const connection = await pool.getConnection();
    await connection.query(
      'UPDATE users SET full_name = ?, role = ?, email = ?, is_active = ?, updated_at = NOW() WHERE id = ?',
      [full_name, role, email, is_active, id]
    );
    connection.release();

    res.json({ message: 'User updated successfully' });
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
});

// Delete user
router.delete('/:id', async (req, res) => {
  try {
    const { id } = req.params;
    const connection = await pool.getConnection();
    const [result] = await connection.query('DELETE FROM users WHERE id = ?', [id]);
    connection.release();

    if (result.affectedRows === 0) {
      return res.status(404).json({ error: 'User not found' });
    }
    res.json({ message: 'User deleted successfully' });
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
});

export default router;
