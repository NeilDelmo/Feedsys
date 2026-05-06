<?php /** Monitoring - Attendance CRUD like students.php */ ?>
<div class="space-y-6">
    <h1 class="text-2xl font-bold">Feeding Monitoring (Attendance)</h1>
    
    <!-- Search/Filter -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
        <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="flex gap-3 flex-1 max-w-md">
                <input id="attendance-search" placeholder="Search attendance..." class="flex-1 form-input">
                <select id="attendance-date-filter" class="form-input">
                    <option value="">All Dates</option>
                </select> <!-- Dynamic dates -->
            </div>
            <button id="add-attendance-btn" class="btn-gradient text-white px-6 py-2.5 rounded-xl font-medium shadow-lg">
                <i data-lucide="plus" class="w-4 h-4 inline mr-2"></i>
                Record Attendance
            </button>
        </div>
    </div>
    
    <!-- Attendance Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table id="attendance-table" class="w-full data-table">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Date</th>
                        <th>Present</th>
                        <th>Meal Received</th>
                        <th>Remarks</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="6" class="text-center py-8 text-gray-500">Loading attendance...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Export Buttons -->
    <div class="flex gap-2">
        <button onclick="exportAttendanceCSV()" class="btn-gradient text-white px-6 py-2.5 rounded-xl font-medium shadow-lg">
            <i data-lucide="download" class="w-4 h-4 inline mr-2"></i>Export CSV
        </button>
        <button onclick="exportAttendancePDF()" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2.5 rounded-xl font-medium shadow-lg">
            <i data-lucide="file-text" class="w-4 h-4 inline mr-2"></i>Export PDF
        </button>
    </div>
</div>

<!-- Attendance Modal -->
<div id="attendance-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50" onclick="closeAttendanceModal()">
    <div class="w-full max-w-lg mx-auto mt-20 p-1" onclick="event.stopPropagation()">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 id="attendance-title" class="text-xl font-bold">Record Attendance</h2>
                    <button onclick="closeAttendanceModal()" class="p-2 hover:bg-gray-100 rounded-lg">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <form id="attendance-form" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-1.5">Student *</label>
                        <select id="attendance-student" required class="form-input">
                            <option>Select student</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1.5">Date *</label>
                        <input type="date" id="attendance-date" required class="form-input">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center gap-2">
                            <input id="attendance-present" type="checkbox" class="w-4 h-4 rounded">
                            <label class="text-sm font-medium">Present</label>
                        </div>
                        <div class="flex items-center gap-2">
                            <input id="attendance-meal" type="checkbox" class="w-4 h-4 rounded">
                            <label class="text-sm font-medium">Meal Received</label>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1.5">Remarks</label>
                        <textarea id="attendance-remarks" rows="2" class="form-input"></textarea>
                    </div>
                    <div class="pt-4 border-t">
                        <button type="submit" class="btn-gradient text-white px-8 py-2.5 rounded-xl font-medium shadow-lg w-full md:w-auto">
                            Save Attendance
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('page-title').textContent = 'Feeding Monitoring';
let attendance = [];
let students = [];

// Load data
async function loadAttendance() {
    try {
        [attendance, students] = await Promise.all([
            window.app.attendanceAPI.getAll(),
            window.app.studentsAPI.getAll()
        ]);
        populateStudentSelect();
        renderTable();
    } catch (error) {
        toast('Failed to load attendance: ' + error.message, 'error');
        document.querySelector('#attendance-table tbody').innerHTML = '<tr><td colspan="6" class="text-center py-8 text-red-500">Failed to load data. Check backend.</td></tr>';
    }
}

function populateStudentSelect() {
    const select = document.getElementById('attendance-student');
    select.innerHTML = '<option>Select student</option>' + 
        students.map(s => `<option value="${s.id}">${s.fullName} (${s.studentId})</option>`).join('');
}

function renderTable() {
    const tbody = document.querySelector('#attendance-table tbody');
    const searchTerm = document.getElementById('attendance-search').value.toLowerCase();
    
    const filtered = attendance.filter(a => 
        a.studentName.toLowerCase().includes(searchTerm)
    );
    
    tbody.innerHTML = filtered.map(a => `
        <tr class="hover:bg-gray-50">
            <td class="font-medium">${a.studentName}</td>
            <td>${new Date(a.date).toLocaleDateString()}</td>
            <td><span class="px-2 py-1 text-xs rounded-full ${a.present ? 'status-normal' : 'bg-gray-100 text-gray-800'} border">${a.present ? 'Yes' : 'No'}</span></td>
            <td><span class="px-2 py-1 text-xs rounded-full ${a.mealReceived ? 'status-normal' : 'bg-gray-100 text-gray-800'} border">${a.mealReceived ? 'Yes' : 'No'}</span></td>
            <td>${a.remarks || '-'}</td>
            <td>
                <button onclick="editAttendance('${a.id}')" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg">
                    <i data-lucide="edit" class="w-4 h-4"></i>
                </button>
                <button onclick="deleteAttendance('${a.id}')" class="p-2 text-red-600 hover:bg-red-50 rounded-lg ml-1">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                </button>
            </td>
        </tr>
    `).join('') || '<tr><td colspan="6" class="text-center py-12 text-gray-500">No attendance records</td></tr>';
    
    lucide.createIcons();
}

// Modal
let editingAttendanceId = null;

function openAttendanceModal(id) {
    editingAttendanceId = id;
    document.getElementById('attendance-title').textContent = id ? 'Edit Attendance' : 'Record Attendance';
    document.getElementById('attendance-form').reset();
    
    if (id) {
        const record = attendance.find(r => r.id === id);
        document.getElementById('attendance-student').value = record.studentId;
        document.getElementById('attendance-date').value = record.date;
        document.getElementById('attendance-present').checked = record.present;
        document.getElementById('attendance-meal').checked = record.mealReceived;
        document.getElementById('attendance-remarks').value = record.remarks;
    }
    
    document.getElementById('attendance-modal').classList.remove('hidden');
}

function closeAttendanceModal() {
    document.getElementById('attendance-modal').classList.add('hidden');
}

async function saveAttendance(e) {
    e.preventDefault();
    
    const attendanceData = {
        studentId: document.getElementById('attendance-student').value,
        date: document.getElementById('attendance-date').value,
        present: document.getElementById('attendance-present').checked,
        mealReceived: document.getElementById('attendance-meal').checked,
        remarks: document.getElementById('attendance-remarks').value,
    };
    
    try {
        if (editingAttendanceId) {
            await window.app.attendanceAPI.update(editingAttendanceId, attendanceData);
            toast('Attendance updated', 'success');
        } else {
            await window.app.attendanceAPI.create(attendanceData);
            toast('Attendance recorded', 'success');
        }
        loadAttendance();
        closeAttendanceModal();
    } catch (error) {
        toast(error.message, 'error');
    }
}

async function deleteAttendance(id) {
    if (confirm('Delete this attendance record?')) {
        try {
            await window.app.attendanceAPI.delete(id);
            toast('Attendance deleted', 'success');
            loadAttendance();
        } catch (error) {
            toast(error.message, 'error');
        }
    }
}

function editAttendance(id) {
    openAttendanceModal(id);
}

// Event listeners
document.getElementById('add-attendance-btn').onclick = () => openAttendanceModal(null);
document.getElementById('attendance-form').onsubmit = saveAttendance;
document.getElementById('attendance-search').oninput = renderTable;

// Exports
function exportAttendanceCSV() {
    window.app.exportCSV(attendance, 'attendance.csv');
}

function exportAttendancePDF() {
    window.app.exportPDF(attendance, 'attendance.pdf', 'Attendance Records');
}

// Init
loadAttendance();
lucide.createIcons();
</script>

