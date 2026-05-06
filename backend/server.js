import express from 'express';
import cors from 'cors';
import dotenv from 'dotenv';
import pool from './config/database.js';

// Import routes
import usersRouter from './routes/users.js';
import studentsRouter from './routes/students.js';
import measurementsRouter from './routes/measurements.js';
import attendanceRouter from './routes/attendance.js';
import authRouter from './routes/auth.js';

dotenv.config();

const app = express();
const PORT = process.env.PORT || 5000;

// Use no prefix on DigitalOcean (because DO strips /api from the path), 
// but use /api on your local machine (Laragon)
const prefix = process.env.DATABASE_URL ? '' : '/api';

// Middleware
app.use(cors({
  origin: '*', // More flexible for deployment
  credentials: true
}));
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

// Request logging middleware
app.use((req, res, next) => {
  console.log(`${req.method} ${req.path}`);
  next();
});

// Test database connection
// Accessible at: .../api/health
app.get(`${prefix}/health`, async (req, res) => {
  try {
    const connection = await pool.getConnection();
    await connection.query('SELECT 1');
    connection.release();
    res.json({ status: 'ok', message: 'Database connected' });
  } catch (error) {
    console.error('Health check failed:', error);
    res.status(500).json({ status: 'error', message: error.message });
  }
});

// Routes using the smart prefix
app.use(`${prefix}/auth`, authRouter);
app.use(`${prefix}/users`, usersRouter);
app.use(`${prefix}/students`, studentsRouter);
app.use(`${prefix}/measurements`, measurementsRouter);
app.use(`${prefix}/attendance`, attendanceRouter);

// Error handling middleware
app.use((err, req, res, next) => {
  console.error('Error:', err.stack);
  const statusCode = err.statusCode || 500;
  const message = err.message || 'Internal Server Error';
  res.status(statusCode).json({ 
    error: 'Internal Server Error', 
    message: message,
    ...(process.env.NODE_ENV === 'development' && { stack: err.stack })
  });
});

// 404 handler
app.use((req, res) => {
  res.status(404).json({ error: 'Not Found', message: `Route ${req.method} ${req.path} not found` });
});

app.listen(PORT, () => {
  console.log(`Feed System API running on PORT: ${PORT}`);
  console.log(`Prefix being used: "${prefix}"`);
});
