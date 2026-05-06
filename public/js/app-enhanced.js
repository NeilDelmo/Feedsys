// FeedSys Enhanced JS Utils - Full API wrappers + exports (Phase 2)
// Builds on existing: apiCall, studentsAPI, Student/Measurement.fromApi, toast

const API_BASE_URL = 'http://localhost:5000/api';

// Enhanced apiCall with auth (PHP session cookie auto-sent via fetch)
async function apiCall(endpoint, options = {}) {
    const defaults = {
        method: 'GET',
        headers: { 
            'Content-Type': 'application/json',
        },
    };
    
    const config = { ...defaults, ...options, credentials: 'include' }; // Send PHP session cookies
    
    try {
        const response = await fetch(`${API_BASE_URL}${endpoint}`, config);
        if (!response.ok) {
            const error = await response.json().catch(() => ({ error: `HTTP ${response.status}` }));
            throw new Error(error.error || error.message || `HTTP ${response.status}`);
        }
        return await response.json();
    } catch (error) {
        console.error(`API Error: ${endpoint}`, error);
        throw error;
    }
}

// Data Transformers (existing + new)
const Student = {
    fromApi(data) {
        return {
            id: data.id.toString(),
            studentId: data.student_id,
            lrn: data.lrn || '',
            fullName: data.full_name,
            grade: data.grade,
            section: data.section,
            sex: data.sex || 'Male',
            dateOfBirth: data.date_of_birth,
            guardian: data.guardian || '',
            contactNumber: data.contact_number || '',
            beneficiary: Boolean(data.beneficiary),
            hasAllergy: Boolean(data.has_allergy),
            allergyNotes: data.allergy_notes || '',
            remarks: data.remarks || '',
            createdAt: data.created_at,
        };
    }
};

const Measurement = {
    fromApi(data) {
        return {
            id: data.id.toString(),
            studentId: data.student_id.toString(),
            studentName: data.student_name,
            measurementType: data.measurement_type,
            date: data.date,
            weight: parseFloat(data.weight),
            height: parseFloat(data.height),
            bmi: parseFloat(data.bmi),
            nutritionalStatus: data.nutritional_status || 'Normal',
            measuredBy: data.measured_by || '',
            remarks: data.remarks || '',
        };
    },
    toApi(formData, autoBmi = null) {
        // Calculate BMI if provided
        let bmi = autoBmi;
        if (formData.weight && formData.height) {
            bmi = formData.weight / Math.pow(formData.height / 100, 2);
        }
        // Nutritional status logic (WHO child BMI)
        const status = getNutritionalStatus(bmi);
        return { ...formData, bmi: bmi?.toFixed(2), nutritional_status: status };
    }
};

const Attendance = {
    fromApi(data) {
        return {
            id: data.id.toString(),
            studentId: data.student_id,
            studentName: data.student_name,
            date: data.date,
            present: Boolean(data.present),
            mealReceived: Boolean(data.meal_received),
            remarks: data.remarks || '',
        };
    }
};

function getNutritionalStatus(bmi) {
    if (!bmi) return 'Unknown';
    if (bmi < 14) return 'Underweight';
    if (bmi < 16) return 'Severe Underweight';
    if (bmi < 18.5) return 'Normal';
    if (bmi < 25) return 'Overweight';
    return 'Obese';
}

// API Wrappers
const studentsAPI = {
    async getAll() { 
        const data = await apiCall('/students');
        return data.map(Student.fromApi);
    },
    async get(id) {
        const data = await apiCall(`/students/${id}`);
        return Student.fromApi(data);
    },
    async create(studentData) {
        const apiData = {
            student_id: studentData.studentId,
            lrn: studentData.lrn,
            full_name: studentData.fullName,
            grade: studentData.grade,
            section: studentData.section,
            sex: studentData.sex,
            date_of_birth: studentData.dateOfBirth,
            guardian: studentData.guardian || '',
            contact_number: studentData.contactNumber,
            beneficiary: studentData.beneficiary ? 1 : 0,
            has_allergy: studentData.hasAllergy ? 1 : 0,
            allergy_notes: studentData.allergyNotes,
            remarks: studentData.remarks,
        };
        return await apiCall('/students', { method: 'POST', body: JSON.stringify(apiData) });
    },
    async update(id, studentData) {
        const apiData = {
            student_id: studentData.studentId,
            lrn: studentData.lrn,
            full_name: studentData.fullName,
            grade: studentData.grade,
            section: studentData.section,
            sex: studentData.sex,
            date_of_birth: studentData.dateOfBirth,
            guardian: studentData.guardian || '',
            contact_number: studentData.contactNumber,
            beneficiary: studentData.beneficiary ? 1 : 0,
            has_allergy: studentData.hasAllergy ? 1 : 0,
            allergy_notes: studentData.allergyNotes,
            remarks: studentData.remarks,
        };
        return await apiCall(`/students/${id}`, { method: 'PUT', body: JSON.stringify(apiData) });
    },
    async delete(id) {
        return await apiCall(`/students/${id}`, { method: 'DELETE' });
    }
};

const measurementsAPI = {
    async getAll() {
        const data = await apiCall('/measurements');
        return data.map(Measurement.fromApi);
    },
    async get(id) {
        const data = await apiCall(`/measurements/${id}`);
        return Measurement.fromApi(data);
    },
    async create(measurementData) {
        const apiData = Measurement.toApi({
            student_id: measurementData.studentId,
            measurement_type: measurementData.measurementType,
            date: measurementData.date,
            weight: parseFloat(measurementData.weight),
            height: parseFloat(measurementData.height),
            measured_by: measurementData.measuredBy,
            remarks: measurementData.remarks,
        });
        return await apiCall('/measurements', { method: 'POST', body: JSON.stringify(apiData) });
    },
    async update(id, measurementData) {
        const apiData = Measurement.toApi(measurementData);
        return await apiCall(`/measurements/${id}`, { method: 'PUT', body: JSON.stringify(apiData) });
    },
    async delete(id) {
        return await apiCall(`/measurements/${id}`, { method: 'DELETE' });
    },
    async getByStudent(studentId) {
        const data = await apiCall(`/measurements/student/${studentId}`);
        return data.map(Measurement.fromApi);
    }
};

const attendanceAPI = {
    async getAll() {
        const data = await apiCall('/attendance');
        return data.map(Attendance.fromApi);
    },
    async get(id) {
        const data = await apiCall(`/attendance/${id}`);
        return Attendance.fromApi(data);
    },
    async create(attendanceData) {
        const apiData = {
            student_id: attendanceData.studentId,
            date: attendanceData.date,
            present: attendanceData.present ? 1 : 0,
            meal_received: attendanceData.mealReceived ? 1 : 0,
            remarks: attendanceData.remarks,
        };
        return await apiCall('/attendance', { method: 'POST', body: JSON.stringify(apiData) });
    },
    async update(id, attendanceData) {
        const apiData = {
            student_id: attendanceData.studentId,
            date: attendanceData.date,
            present: attendanceData.present ? 1 : 0,
            meal_received: attendanceData.mealReceived ? 1 : 0,
            remarks: attendanceData.remarks,
        };
        return await apiCall(`/attendance/${id}`, { method: 'PUT', body: JSON.stringify(apiData) });
    },
    async delete(id) {
        return await apiCall(`/attendance/${id}`, { method: 'DELETE' });
    },
    async getByStudent(studentId) {
        const data = await apiCall(`/attendance/student/${studentId}`);
        return data.map(Attendance.fromApi);
    }
};

// Export Utils (CSV native, PDF jsPDF CDN needed in header)
function exportCSV(data, filename = 'data.csv', columns = []) {
    const headers = columns.length ? columns : Object.keys(data[0] || {});
    const csv = [
        headers.join(','),
        ...data.map(row => headers.map(h => JSON.stringify(row[h] || '')).join(','))
    ].join('\n');
    
    const blob = new Blob([csv], { type: 'text/csv' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = filename;
    a.click();
    URL.revokeObjectURL(url);
    toast('CSV exported successfully', 'success');
}

function exportPDF(data, filename = 'data.pdf', title = 'Report') {
    // jsPDF integration
    if (typeof window.jsPDF === 'undefined') {
        toast('PDF export requires jsPDF library', 'error');
        return;
    }
    
    const { jsPDF } = window;
    const doc = new jsPDF();
    const pageWidth = doc.internal.pageSize.getWidth();
    const pageHeight = doc.internal.pageSize.getHeight();
    
    // Title
    doc.setFontSize(16);
    doc.text(title, pageWidth / 2, 15, { align: 'center' });
    
    // Date
    doc.setFontSize(10);
    doc.text(`Generated: ${new Date().toLocaleDateString()}`, pageWidth / 2, 25, { align: 'center' });
    
    // Table data
    if (data && data.length > 0) {
        const headers = Object.keys(data[0]);
        const tableData = data.map(row => headers.map(h => row[h] || ''));
        
        doc.autoTable({
            head: [headers],
            body: tableData,
            startY: 35,
            margin: 10,
            didDrawPage: function(data) {
                const str = `Page ${data.pageCount}`;
                doc.setFontSize(10);
                doc.text(str, pageWidth - 20, pageHeight - 10);
            }
        });
    }
    
    doc.save(filename);
    toast('PDF exported successfully', 'success');
}

// Users API
const usersAPI = {
    async getAll() {
        const data = await apiCall('/users');
        return data;
    },
    async get(id) {
        const data = await apiCall(`/users/${id}`);
        return data;
    },
    async create(userData) {
        const apiData = {
            username: userData.username,
            password: userData.password,
            full_name: userData.fullName,
            email: userData.email,
            role: userData.role,
        };
        return await apiCall('/users', { method: 'POST', body: JSON.stringify(apiData) });
    },
    async update(id, userData) {
        const apiData = {
            username: userData.username,
            full_name: userData.fullName,
            email: userData.email,
            role: userData.role,
        };
        return await apiCall(`/users/${id}`, { method: 'PUT', body: JSON.stringify(apiData) });
    },
    async delete(id) {
        return await apiCall(`/users/${id}`, { method: 'DELETE' });
    }
};

// Load current user (async API or PHP DOM)
async function loadUser() {
    try {
        const user = await apiCall('/auth/me');
        return user;
    } catch {
        return null; // Fallback to PHP DOM echo in pages
    }
}

// Recent activities - returns last 10 activities from measurements and attendance
async function getRecentActivities() {
    try {
        const [measurements, attendance] = await Promise.all([
            measurementsAPI.getAll(),
            attendanceAPI.getAll()
        ]);
        
        const activities = [];
        
        // Add measurements
        measurements.slice(0, 5).forEach(m => {
            activities.push({
                id: `m-${m.id}`,
                type: 'measurement',
                description: `${m.studentName} - BMI: ${m.bmi} (${m.nutritionalStatus})`,
                date: new Date(m.date),
                icon: 'activity'
            });
        });
        
        // Add attendance
        attendance.slice(0, 5).forEach(a => {
            activities.push({
                id: `a-${a.id}`,
                type: 'attendance',
                description: `${a.studentName} - ${a.present ? 'Present' : 'Absent'} (${a.mealReceived ? 'Meal received' : 'No meal'})`,
                date: new Date(a.date),
                icon: 'check-circle'
            });
        });
        
        // Sort by date, newest first
        return activities.sort((a, b) => b.date - a.date).slice(0, 10);
    } catch (error) {
        console.error('Failed to load recent activities:', error);
        return [];
    }
}

// Global app export
window.app = {
    apiCall,
    studentsAPI,
    measurementsAPI,
    attendanceAPI,
    usersAPI,
    Student,
    Measurement,
    Attendance,
    exportCSV,
    exportPDF,
    loadUser,
    getRecentActivities,
    toast
};

