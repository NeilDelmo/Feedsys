# Feed System Backend API

This is the Node.js/Express backend API for the Feed System application. It connects to a MySQL database (XAMPP phpMyAdmin) and provides REST API endpoints for the React frontend.

## Prerequisites

- Node.js (v16 or higher)
- XAMPP with MySQL running
- The `feed_system` database imported from `feed_system.sql`

## Installation

1. Install dependencies:
```bash
npm install
```

2. Create a `.env` file (or rename `.env.example` to `.env`) and configure your database connection:

```env
DB_HOST=localhost
DB_USER=root
DB_PASSWORD=
DB_NAME=feed_system
PORT=5000
NODE_ENV=development
API_URL=http://localhost:5000
```

## Database Setup

1. Open XAMPP Control Panel and start MySQL
2. Open phpMyAdmin at `http://localhost/phpmyadmin`
3. Create a new database named `feed_system`
4. Import the `feed_system.sql` file into the database

## Running the Server

### Development Mode (with auto-reload)
```bash
npm run dev
```

### Production Mode
```bash
npm start
```

The server will start on `http://localhost:5000`

## API Endpoints

### Authentication
- `POST /api/auth/login` - Login user

### Users
- `GET /api/users` - Get all users
- `GET /api/users/:id` - Get user by ID
- `POST /api/users` - Create new user
- `PUT /api/users/:id` - Update user
- `DELETE /api/users/:id` - Delete user

### Students
- `GET /api/students` - Get all students
- `GET /api/students/:id` - Get student by ID
- `POST /api/students` - Create new student
- `PUT /api/students/:id` - Update student
- `DELETE /api/students/:id` - Delete student

### Measurements
- `GET /api/measurements` - Get all measurements
- `GET /api/measurements/:id` - Get measurement by ID
- `GET /api/measurements/student/:student_id` - Get measurements for a student
- `POST /api/measurements` - Create new measurement
- `PUT /api/measurements/:id` - Update measurement
- `DELETE /api/measurements/:id` - Delete measurement

### Attendance
- `GET /api/attendance` - Get all attendance records
- `GET /api/attendance/:id` - Get attendance record by ID
- `GET /api/attendance/student/:student_id` - Get attendance for a student
- `POST /api/attendance` - Create new attendance record
- `PUT /api/attendance/:id` - Update attendance record
- `DELETE /api/attendance/:id` - Delete attendance record

## Health Check

To verify the server and database connection:
```
GET http://localhost:5000/health
```

## Directory Structure

```
backend/
├── config/
│   └── database.js      # MySQL connection pool
├── routes/
│   ├── auth.js         # Authentication endpoints
│   ├── users.js        # Users CRUD endpoints
│   ├── students.js     # Students CRUD endpoints
│   ├── measurements.js # Measurements CRUD endpoints
│   └── attendance.js   # Attendance CRUD endpoints
├── server.js           # Main Express server
├── package.json        # Dependencies
├── .env                # Environment variables (local)
├── .env.example        # Example environment variables
└── .gitignore         # Git ignore rules
```

## Notes

- The API uses CORS to allow requests from `http://localhost:5173` (Vite frontend)
- Password hashing is noted as TODO in the auth routes - implement bcrypt for production
- JWT token implementation is recommended for production security
- All timestamps use `NOW()` from MySQL for consistency
