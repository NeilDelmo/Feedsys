<?php /** Students - CRUD table + modals, exact React student-profiles.tsx */ ?>
<div class="space-y-6">
    <h1 class="text-2xl font-bold">Student Profiles</h1>
    
    <!-- Search/Filter -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
        <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="flex gap-3 flex-1 max-w-md">
                <input id="search-input" placeholder="Search students..." class="flex-1 form-input">
                <select id="grade-filter" class="form-input">
                    <option value="">All Grades</option>
                    <option value="Grade 1">Grade 1</option>
                    <option value="Grade 2">Grade 2</option>
                    <option value="Grade 3">Grade 3</option>
                </select>
            </div>
            <button id="add-student-btn" class="btn-gradient text-white px-6 py-2.5 rounded-xl font-medium shadow-lg">
                <i data-lucide="user-plus" class="w-4 h-4 inline mr-2"></i>
                Add Student
            </button>
        </div>
    </div>
    
    <!-- Students Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table id="students-table" class="w-full data-table">
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>LRN</th>
                        <th>Name</th>
                        <th>Grade/Section</th>
                        <th>Sex</th>
                        <th>Beneficiary</th>
                        <th>Allergy</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="8" class="text-center py-8 text-gray-500">Loading students...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Export Buttons -->
    <div class="flex gap-2">
        <button onclick="exportStudentsCSV()" class="btn-gradient text-white px-6 py-2.5 rounded-xl font-medium shadow-lg">
            <i data-lucide="download" class="w-4 h-4 inline mr-2"></i>Export CSV
        </button>
        <button onclick="exportStudentsPDF()" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2.5 rounded-xl font-medium shadow-lg">
            <i data-lucide="file-text" class="w-4 h-4 inline mr-2"></i>Export PDF
        </button>
    </div>
</div>

<!-- Add/Edit Student Modal -->
<div id="student-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50" onclick="closeStudentModal()">
    <div class="w-full max-w-2xl mx-auto mt-20 p-1" onclick="event.stopPropagation()">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 id="modal-title" class="text-xl font-bold">Add Student</h2>
                    <button onclick="closeStudentModal()" class="p-2 hover:bg-gray-100 rounded-lg">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <form id="student-form" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1.5">Student ID *</label>
                            <input id="student-id" required class="form-input">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1.5">LRN</label>
                            <input id="lrn" class="form-input">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1.5">Full Name *</label>
                        <input id="full-name" required class="form-input">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1.5">Grade *</label>
                            <select id="grade" required class="form-input">
                                <option>Grade 1</option>
                                <option>Grade 2</option>
                                <option>Grade 3</option>
                                <option>Grade 4</option>
                                <option>Grade 5</option>
                                <option>Grade 6</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1.5">Section *</label>
                            <input id="section" required class="form-input">
                        </div>
                    </div>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1.5">Sex</label>
                            <select id="sex" class="form-input">
                                <option>Male</option>
                                <option>Female</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1.5">Date of Birth</label>
                            <input type="date" id="dob" class="form-input">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1.5">Guardian</label>
                            <input id="guardian" class="form-input">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1.5">Contact Number</label>
                            <input id="contact" class="form-input">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center gap-2">
                            <input id="beneficiary" type="checkbox" class="w-4 h-4 rounded">
                            <label class="text-sm">Beneficiary</label>
                        </div>
                        <div class="flex items-center gap-2">
                            <input id="allergy" type="checkbox" class="w-4 h-4 rounded">
                            <label class="text-sm">Has Allergy</label>
                        </div>
                    </div>
                    <div id="allergy-notes-group" class="hidden">
                        <label class="block text-sm font-medium mb-1.5">Allergy Notes</label>
                        <textarea id="allergy-notes" rows="2" class="form-input"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1.5">Remarks</label>
                        <textarea id="remarks" rows="2" class="form-input"></textarea>
                    </div>
                    <div class="pt-4 border-t">
                        <button type="submit" id="save-student" class="btn-gradient text-white px-8 py-2.5 rounded-xl font-medium shadow-lg w-full md:w-auto">
                            Save Student
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function exportStudentsCSV() {
    const columns = ['studentId', 'lrn', 'fullName', 'grade', 'section', 'sex', 'beneficiary'];
    window.app.exportCSV(students, 'students.csv', columns);
}

function exportStudentsPDF() {
    window.app.exportPDF(students, 'students.pdf', 'Student Profiles');
}

document.getElementById('page-title').textContent = 'Student Profiles';
let students = [];
let editingStudentId = null;

// Load students
async function loadStudents() {
    try {
        students = await window.app.studentsAPI.getAll();
        renderTable();
    } catch (error) {
        toast('Failed to load students: ' + error.message, 'error');
        document.querySelector('#students-table tbody').innerHTML = '<tr><td colspan="8" class="text-center py-8 text-red-500">Failed to load data. Check backend.</td></tr>';
    }
}


function renderTable() {
    const tbody = document.querySelector('#students-table tbody');
    const searchTerm = document.getElementById('search-input').value.toLowerCase();
    const gradeFilter = document.getElementById('grade-filter').value;
    
    const filteredStudents = students.filter(student => 
        student.fullName.toLowerCase().includes(searchTerm) &&
        (!gradeFilter || student.grade === gradeFilter)
    );
    
    if (filteredStudents.length === 0) {
        tbody.innerHTML = '<tr><td colspan="8" class="text-center py-12 text-gray-500">No students found</td></tr>';
        return;
    }
    
    tbody.innerHTML = filteredStudents.map(student => `
        <tr class="hover:bg-gray-50">
            <td class="font-mono font-medium">${student.studentId}</td>
            <td>${student.lrn}</td>
            <td class="font-medium">${student.fullName}</td>
            <td>${student.grade} ${student.section}</td>
            <td><span class="px-2 py-1 text-xs rounded-full bg-${student.sex === 'Male' ? 'blue' : 'purple'}-100 text-${student.sex === 'Male' ? 'blue' : 'purple'}-800">${student.sex}</span></td>
            <td><span class="px-2 py-1 text-xs rounded-full ${student.beneficiary ? 'status-normal' : 'bg-gray-100 text-gray-800'} border">${student.beneficiary ? 'Yes' : 'No'}</span></td>
            <td>
                ${student.hasAllergy ? `<span class="status-underweight px-2 py-1 text-xs rounded-full">Yes</span> ${student.allergyNotes ? `<br><small class="text-gray-500">${student.allergyNotes}</small>` : ''}` : 'No'}
            </td>
            <td class="flex gap-2">
                <button onclick="editStudent('${student.id}')" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg" title="Edit">
                    <i data-lucide="edit" class="w-4 h-4"></i>
                </button>
                <button onclick="deleteStudent('${student.id}')" class="p-2 text-red-600 hover:bg-red-50 rounded-lg" title="Delete">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                </button>
            </td>
        </tr>
    `).join('');
    
    lucide.createIcons();
}

// Modal handlers
document.getElementById('add-student-btn').onclick = () => openStudentModal(null);
document.getElementById('student-form').onsubmit = saveStudent;
document.getElementById('allergy').onchange = function() {
    document.getElementById('allergy-notes-group').classList.toggle('hidden', !this.checked);
};

// Search/Filter
document.getElementById('search-input').oninput = renderTable;
document.getElementById('grade-filter').onchange = renderTable;

function openStudentModal(studentId) {
    editingStudentId = studentId;
    document.getElementById('modal-title').textContent = studentId ? 'Edit Student' : 'Add Student';
    document.getElementById('student-form').reset();
    
    if (studentId) {
        const student = students.find(s => s.id === studentId);
        document.getElementById('student-id').value = student.studentId;
        document.getElementById('lrn').value = student.lrn;
        document.getElementById('full-name').value = student.fullName;
        document.getElementById('grade').value = student.grade;
        document.getElementById('section').value = student.section;
        document.getElementById('sex').value = student.sex;
        document.getElementById('dob').value = student.dateOfBirth;
        document.getElementById('contact').value = student.contactNumber;
        document.getElementById('beneficiary').checked = student.beneficiary;
        document.getElementById('allergy').checked = student.hasAllergy;
        document.getElementById('allergy-notes').value = student.allergyNotes;
        document.getElementById('remarks').value = student.remarks;
        
        document.getElementById('allergy-notes-group').classList.toggle('hidden', !student.hasAllergy);
    }
    
    document.getElementById('student-modal').classList.remove('hidden');
}

function closeStudentModal() {
    document.getElementById('student-modal').classList.add('hidden');
}

async function saveStudent(e) {
    e.preventDefault();
    
    const studentData = {
        studentId: document.getElementById('student-id').value,
        lrn: document.getElementById('lrn').value,
        fullName: document.getElementById('full-name').value,
        grade: document.getElementById('grade').value,
        section: document.getElementById('section').value,
        sex: document.getElementById('sex').value,
        dateOfBirth: document.getElementById('dob').value,
        guardian: document.getElementById('guardian')?.value || '',
        contactNumber: document.getElementById('contact').value,
        beneficiary: document.getElementById('beneficiary').checked,
        hasAllergy: document.getElementById('allergy').checked,
        allergyNotes: document.getElementById('allergy-notes').value,
        remarks: document.getElementById('remarks').value,
    };
    
    try {
        if (editingStudentId) {
            await window.app.studentsAPI.update(editingStudentId, studentData);
            toast('Student updated successfully', 'success');
        } else {
            await window.app.studentsAPI.create(studentData);
            toast('Student added successfully', 'success');
        }
        loadStudents();
        closeStudentModal();
    } catch (error) {
        toast(error.message, 'error');
    }
}


async function deleteStudent(id) {
    if (confirm('Delete this student?')) {
        try {
            await window.app.studentsAPI.delete(id);
            toast('Student deleted', 'success');
            loadStudents();
        } catch (error) {
            toast(error.message, 'error');
        }
    }
}

function editStudent(id) {
    const student = students.find(s => s.id === id);
    if (student) openStudentModal(id);
}


// Init
loadStudents();
lucide.createIcons();
</script>

