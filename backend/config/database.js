import mysql from 'mysql2/promise.js';

/**
 * Database connection pool.
 * Uses DATABASE_URL for cloud environments (DigitalOcean)
 * or individual environment variables for local development.
 */
const connectionConfig = process.env.DATABASE_URL 
  ? process.env.DATABASE_URL 
  : {
      host: process.env.DB_HOST || 'localhost',
      user: process.env.DB_USER || 'root',
      password: process.env.DB_PASSWORD || '',
      database: process.env.DB_NAME || 'feed_system',
      waitForConnections: true,
      connectionLimit: 10,
      queueLimit: 0
    };

const pool = mysql.createPool(connectionConfig);

export default pool;
