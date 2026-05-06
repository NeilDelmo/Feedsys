-- Realistic seed data for Feed System DB
-- Run: mysql -u root feed_system < seed_data.sql
-- Assumes empty tables (students/measurements/attendance = 0)

USE feed_system;

-- 10 Students (Grade 1-6)
INSERT INTO students (student_id, lrn, full_name, grade, section, sex, date_of_birth, guardian, contact_number, beneficiary, has_allergy, allergy_notes, remarks, created_at, updated_at) VALUES
('S001', '123456789012', 'Juan Dela Cruz', 'Grade 1', 'Star', 'Male', '2019-05-15', 'Maria Dela Cruz', '09171234567', 1, 0, NULL, NULL, NOW(), NOW()),
('S002', '123456789013', 'Maria Santos', 'Grade 1', 'Moon', 'Female', '2019-06-20', 'Pedro Santos', '09172345678', 1, 1, 'Peanut allergy', NULL, NOW(), NOW()),
('S003', '123456789014', 'Pedro Garcia', 'Grade 2', 'Sun', 'Male', '2018-08-10', 'Ana Garcia', '09173456789', 1, 0, NULL, 'Active', NOW(), NOW()),
('S004', '123456789015', 'Ana Reyes', 'Grade 2', 'Cloud', 'Female', '2018-04-25', 'Jose Reyes', '09174567890', 0, 0, NULL, NULL, NOW(), NOW()),
('S005', '123456789016', 'Luis Mendoza', 'Grade 3', 'Star', 'Male', '2017-11-30', 'Carmen Mendoza', '09175678901', 1, 0, NULL, NULL, NOW(), NOW()),
('S006', '123456789017', 'Sofia Lopez', 'Grade 3', 'Moon', 'Female', '2017-09-12', 'Miguel Lopez', '09176789012', 1, 0, NULL, NULL, NOW(), NOW()),
('S007', '123456789018', 'Carlo Villanueva', 'Grade 4', 'Sun', 'Male', '2016-03-05', 'Luz Villanueva', '09177890123', 1, 1, 'Dairy allergy', NULL, NOW(), NOW()),
('S008', '123456789019', 'Julia Tan', 'Grade 4', 'Cloud', 'Female', '2016-07-18', 'Henry Tan', '09178901234', 0, 0, NULL, NULL, NOW(), NOW()),
('S009', '123456789020', 'Mark Ramos', 'Grade 5', 'Star', 'Male', '2015-12-22', 'Rosa Ramos', '09179012345', 1, 0, NULL, NULL, NOW(), NOW()),
('S010', '123456789021', 'Lara Cruz', 'Grade 6', 'Moon', 'Female', '2015-02-14', 'Victor Cruz', '09180123456', 1, 0, NULL, 'Good attendance', NOW(), NOW());

-- 20 Measurements (linked to students 1-10, 2 each)
INSERT INTO measurements (student_id, student_name, measurement_type, date, weight, height, bmi, nutritional_status, measured_by, remarks, created_at, updated_at) VALUES
(1, 'Juan Dela Cruz', 'Initial', '2024-10-01', 18.5, 110.5, 15.15, 'Normal', 'Nurse A', NULL, NOW(), NOW()),
(1, 'Juan Dela Cruz', 'Monthly', '2024-11-01', 19.2, 112.0, 15.29, 'Normal', 'Nurse A', 'Good gain', NOW(), NOW()),
(2, 'Maria Santos', 'Initial', '2024-10-01', 16.8, 108.0, 14.40, 'Underweight', 'Nurse B', NULL, NOW(), NOW()),
(2, 'Maria Santos', 'Monthly', '2024-11-01', 17.5, 109.5, 14.60, 'Underweight', 'Nurse B', 'Supplement recommended', NOW(), NOW()),
(3, 'Pedro Garcia', 'Initial', '2024-10-01', 22.0, 118.0, 15.77, 'Normal', 'Nurse A', NULL, NOW(), NOW()),
(3, 'Pedro Garcia', 'Monthly', '2024-11-01', 22.8, 119.5, 15.99, 'Normal', 'Nurse A', NULL, NOW(), NOW()),
(4, 'Ana Reyes', 'Initial', '2024-10-01', 20.5, 115.0, 15.48, 'Normal', 'Nurse B', NULL, NOW(), NOW()),
(4, 'Ana Reyes', 'Monthly', '2024-11-01', 21.2, 116.5, 15.64, 'Normal', 'Nurse B', NULL, NOW(), NOW()),
(5, 'Luis Mendoza', 'Initial', '2024-10-01', 25.0, 125.0, 16.00, 'Normal', 'Nurse A', NULL, NOW(), NOW()),
(5, 'Luis Mendoza', 'Monthly', '2024-11-01', 25.8, 126.5, 16.13, 'Normal', 'Nurse A', NULL, NOW(), NOW()),
(6, 'Sofia Lopez', 'Initial', '2024-10-01', 23.5, 122.0, 15.79, 'Normal', 'Nurse B', NULL, NOW(), NOW()),
(6, 'Sofia Lopez', 'Monthly', '2024-11-01', 24.3, 123.5, 15.94, 'Normal', 'Nurse B', NULL, NOW(), NOW()),
(7, 'Carlo Villanueva', 'Initial', '2024-10-01', 28.0, 130.0, 16.55, 'Normal', 'Nurse A', NULL, NOW(), NOW()),
(7, 'Carlo Villanueva', 'Monthly', '2024-11-01', 29.0, 131.5, 16.79, 'Normal', 'Nurse A', NULL, NOW(), NOW()),
(8, 'Julia Tan', 'Initial', '2024-10-01', 26.5, 128.0, 16.18, 'Normal', 'Nurse B', NULL, NOW(), NOW()),
(8, 'Julia Tan', 'Monthly', '2024-11-01', 27.4, 129.5, 16.35, 'Normal', 'Nurse B', NULL, NOW(), NOW()),
(9, 'Mark Ramos', 'Initial', '2024-10-01', 32.0, 138.0, 16.81, 'Normal', 'Nurse A', NULL, NOW(), NOW()),
(9, 'Mark Ramos', 'Monthly', '2024-11-01', 33.2, 139.5, 17.05, 'Normal', 'Nurse A', NULL, NOW(), NOW()),
(10, 'Lara Cruz', 'Initial', '2024-10-01', 30.5, 135.0, 16.76, 'Normal', 'Nurse B', NULL, NOW(), NOW()),
(10, 'Lara Cruz', 'Monthly', '2024-11-01', 31.5, 136.5, 16.90, 'Normal', 'Nurse B', NULL, NOW(), NOW());

-- 30 Attendance records (students 1-10, recent 2 weeks, mix)
INSERT INTO attendance (student_id, date, present, meal_received, remarks, created_at, updated_at) VALUES
(1, '2024-11-15', 1, 1, NULL, NOW(), NOW()),
(1, '2024-11-14', 1, 1, NULL, NOW(), NOW()),
(1, '2024-11-13', 0, 0, 'Sick', NOW(), NOW()),
(2, '2024-11-15', 1, 1, NULL, NOW(), NOW()),
(2, '2024-11-14', 1, 0, 'Allergy reaction', NOW(), NOW()),
(2, '2024-11-13', 1, 1, NULL, NOW(), NOW()),
(3, '2024-11-15', 1, 1, NULL, NOW(), NOW()),
(3, '2024-11-14', 1, 1, NULL, NOW(), NOW()),
(3, '2024-11-13', 1, 1, NULL, NOW(), NOW()),
(4, '2024-11-15', 1, 1, NULL, NOW(), NOW()),
(5, '2024-11-15', 1, 1, NULL, NOW(), NOW()),
(6, '2024-11-15', 1, 1, NULL, NOW(), NOW()),
(7, '2024-11-15', 1, 0, 'Allergy', NOW(), NOW()),
(8, '2024-11-15', 1, 1, NULL, NOW(), NOW()),
(9, '2024-11-15', 1, 1, NULL, NOW(), NOW()),
(10, '2024-11-15', 1, 1, NULL, NOW(), NOW()),
(1, '2024-11-12', 1, 1, NULL, NOW(), NOW()),
(2, '2024-11-12', 1, 1, NULL, NOW(), NOW()),
(3, '2024-11-12', 1, 1, NULL, NOW(), NOW()),
(4, '2024-11-12', 1, 1, NULL, NOW(), NOW()),
(5, '2024-11-12', 1, 1, NULL, NOW(), NOW()),
(6, '2024-11-12', 1, 1, NULL, NOW(), NOW()),
(7, '2024-11-12', 1, 1, NULL, NOW(), NOW()),
(8, '2024-11-12', 1, 1, NULL, NOW(), NOW()),
(9, '2024-11-12', 1, 1, NULL, NOW(), NOW()),
(10, '2024-11-12', 1, 1, NULL, NOW(), NOW());
