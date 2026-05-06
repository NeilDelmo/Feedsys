# Feed System - Database Connection Setup Guide

This guide will help you connect the Feed System React application to the MySQL database in XAMPP phpMyAdmin.

## Quick Start

### Prerequisites
- XAMPP installed and running (MySQL service active)
- Node.js installed
- The `feed_system.sql` file ready to import

### Step 1: Set Up the Database

1. **Start XAMPP MySQL:**
   - Open XAMPP Control Panel
   - Click "Start" for MySQL service

2. **Import the SQL Schema:**
   - Open phpMyAdmin: `http://localhost/phpmyadmin`
   - Create a new database named `feed_system`
   - Click on the new database
   - Go to "Import" tab
   - Select the `feed_system.sql` file from the root directory
   - Click "Import"

3. **Verify the Tables:**
   - You should see these tables:
     - users (with admin, encoder, viewer accounts)
     - students
     - measurements
     - attendance
     - migrations

### Step 2: Set Up the Backend

1. **Navigate to backend directory:**
   ```bash
   cd backend
   ```

2. **Install dependencies:**
   ```bash
   npm install
   ```

3. **The `.env` file is pre-configured with:**
   - `DB_HOST=localhost`
   - `DB_USER=root`
   - `DB_PASSWORD=` (blank, as per XAMPP default)
   - `DB_NAME=feed_system`
   - `PORT=5000`

4. **Start the backend server:**
   ```bash
   npm run dev
   ```
   
   You should see:
   ```
   Feed System API running on http://localhost:5000
   Environment: development
   ```

### Step 3: Set Up the Frontend

1. **In a new terminal, navigate to the root directory:**
   ```bash
   # Make sure you're not in the backend folder
   cd ..
   ```

2. **Install frontend dependencies (if not already done):**
   ```bash
   npm install
   ```

3. **The `.env` file is pre-configured with:**
   - `VITE_API_URL=http://localhost:5000/api`

4. **Start the frontend:**
   ```bash
   npm run dev
   ```

   You should see:
   ```
   ➜  Local:   http://localhost:5173
   ```

### Step 4: Test the Connection

1. **Open the frontend in your browser:**
   - Visit `http://localhost:5173`

2. **Check the API health:**
   - Open `http://localhost:5000/health` in your browser
   - You should see: `{"status":"ok","message":"Database connected"}`

3. **Test Login (if implemented):**
   - Use the pre-loaded credentials from the database:
     - Username: `admin` / Password: (stored as bcrypt hash)
     - Username: `encoder` 
     - Username: `viewer`

## API Integration

The frontend uses the API service located at `src/lib/api.ts` which provides:

### Available API Functions

```typescript
// Import the API functions
import { authAPI, usersAPI, studentsAPI, measurementsAPI, attendanceAPI } from '@/lib/api'

// Example usage:
const students = await studentsAPI.getAll()
const measurements = await measurementsAPI.getByStudentId(studentId)
const user = await usersAPI.create({...})
```

## Directory Structure

```
FeedSys/
├── backend/                    # Express backend server
│   ├── config/                # Database config
│   ├── routes/                # API endpoints
│   ├── server.js              # Main server file
│   ├── .env                   # Database credentials
│   └── package.json           # Backend dependencies
├── src/
│   ├── lib/
│   │   └── api.ts             # Frontend API client
│   ├── pages/                 # Page components
│   ├── components/            # UI components
│   └── ...
├── .env                       # Frontend config
├── package.json               # Frontend dependencies
├── feed_system.sql            # Database schema
└── README.md                  # This file
```

## Troubleshooting

### "Connection refused" or "Cannot connect to database"
- Check if MySQL is running in XAMPP
- Verify database name is `feed_system`
- Check `.env` file has correct credentials

### Backend not responding
- Ensure backend is running: `npm run dev` in `/backend` folder
- Check if port 5000 is in use: `netstat -ano | findstr :5000`

### CORS errors
- Backend is configured to accept requests from `http://localhost:5173`
- If using a different frontend URL, update the CORS config in `backend/server.js`

### Database not importing
- Ensure the database `feed_system` exists
- Check file path is correct
- Verify `.sql` file is not corrupted

## Stopping the Services

1. **Stop Frontend:** Press `Ctrl+C` in the frontend terminal
2. **Stop Backend:** Press `Ctrl+C` in the backend terminal
3. **Stop MySQL:** Click "Stop" in XAMPP Control Panel

## Next Steps

- Implement proper password hashing with bcrypt (see `backend/routes/auth.js`)
- Add JWT authentication tokens for session management
- Create React components that use the API functions
- Add error handling and loading states in the UI
- Implement form validation on both frontend and backend

## Support Files

- **Frontend API Client:** [src/lib/api.ts](src/lib/api.ts)
- **Backend Documentation:** [backend/README.md](backend/README.md)
- **Database Schema:** [feed_system.sql](feed_system.sql)
