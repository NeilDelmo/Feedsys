<?php /** Measurements - Full CRUD + charts like React measurements.tsx */ ?>
<div class="space-y-6">
    <h1 class="text-2xl font-bold">Anthropometric Measurements</h1>
    
    <!-- Search/Filter -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
        <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="flex gap-3 flex-1 max-w-md">
                <input id="measurement-search" placeholder="Search measurements..." class="flex-1 form-input">
                <select id="measurement-type-filter" class="form-input">
                    <option value="">All Types</option>
                    <option value="Baseline">Baseline</option>
                    <option value="Monthly">Monthly</option>
                    <option value="Endline">Endline</option>
                </select>
            </div>
            <button id="add-measurement-btn" class="btn-gradient text-white px-6 py-2.5 rounded-xl font-medium shadow-lg">
                <i data-lucide="plus" class="w-4 h-4 inline mr-2"></i>
                Add Measurement
            </button>
        </div>
    </div>
    
    <!-- Measurements Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table id="measurements-table" class="w-full data-table">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Type</th>
                        <th>Date</th>
                        <th>Weight (kg)</th>
                        <th>Height (cm)</th>
                        <th>BMI</th>
                        <th>Status</th>
                        <th>Measured By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="9" class="text-center py-8 text-gray-500">Loading measurements...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Measurement Modal -->
<div id="measurement-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50" onclick="closeMeasurementModal()">
    <div class="w-full max-w-md mx-auto mt-20 p-1" onclick="event.stopPropagation()">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <div class="p-6 border-b">
                <h2 id="measurement-title" class="text-xl font-bold">Add Measurement</h2>
            </div>
            <div class="p-6">
                <form id="measurement-form" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-1.5">Student</label>
                        <select id="measurement-student" required class="form-input">
                            <option>Select student</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1.5">Measurement Type</label>
                        <select id="measurement-type" required class="form-input">
                            <option value="Baseline">Baseline</option>
                            <option value="Monthly">Monthly</option>
                            <option value="Endline">Endline</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1.5">Date</label>
                            <input type="date" id="measurement-date" required class="form-input">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1.5">Measured By</label>
                            <input id="measured-by" required class="form-input">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1.5">Weight (kg)</label>
                            <input type="number" step="0.1" id="weight" class="form-input" onchange="calculateBMI()">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1.5">Height (cm)</label>
                            <input type="number" step="0.1" id="height" class="form-input" onchange="calculateBMI()">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1.5">BMI</label>
                            <input id="bmi" readonly class="form-input bg-gray-50">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1.5">Remarks</label>
                        <textarea id="measurement-remarks" rows="2" class="form-input"></textarea>
                    </div>
                    <button type="submit" class="btn-gradient text-white w-full py-3 rounded-xl font-medium shadow-lg">
                        Save Measurement
                    </button>
                </form>
            </div>
        </div>
        </div>
        <div class="flex gap-2 pt-4">
            <button onclick="exportMeasurementsCSV()" class="btn-gradient px-4 py-2 rounded-xl text-white font-medium shadow-lg">
                <i data-lucide="download" class="w-4 h-4 inline mr-2"></i>Export CSV
            </button>
            <button onclick="exportMeasurementsPDF()" class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-xl text-white font-medium shadow-lg">
                <i data-lucide="file-text" class="w-4 h-4 inline mr-2"></i>Export PDF
            </button>
        </div>
    </div>
</div>

<script>
function exportMeasurementsCSV() {
    const columns = ['studentName', 'measurementType', 'date', 'weight', 'height', 'bmi', 'nutritionalStatus'];
    window.app.exportCSV(measurements, 'measurements.csv', columns);
}

function exportMeasurementsPDF() {
    window.app.exportPDF(measurements, 'measurements.pdf', 'Anthropometric Measurements');
}

document.getElementById('page-title').textContent = 'Measurements';
let measurements = [];
let students = [];

// Load data
async function loadMeasurements() {
    try {
        [measurements, students] = await Promise.all([
            window.app.measurementsAPI.getAll(),
            window.app.studentsAPI.getAll()
        ]);
        
        populateStudentSelect();
        renderTable();
    } catch (error) {
        toast('Failed to load data: ' + error.message, 'error');
        document.querySelector('#measurements-table tbody').innerHTML = '<tr><td colspan="9" class="text-center py-8 text-red-500">Failed to load data. Check backend.</td></tr>';
    }
}


function populateStudentSelect() {
    const select = document.getElementById('measurement-student');
    select.innerHTML = '<option>Select student</option>' + 
        students.map(s => 
            `<option value="${s.id}">${s.fullName} (${s.studentId})</option>`
        ).join('');
}

function renderTable() {
    const tbody = document.querySelector('#measurements-table tbody');
    const searchTerm = document.getElementById('measurement-search').value.toLowerCase();
    const typeFilter = document.getElementById('measurement-type-filter').value;
    
    const filtered = measurements.filter(m => 
        m.studentName.toLowerCase().includes(searchTerm) &&
        (!typeFilter || m.measurementType === typeFilter)
    );
    
    tbody.innerHTML = filtered.map(m => `
        <tr class="hover:bg-gray-50">
            <td class="font-medium">${m.studentName}</td>
            <td><span class="px-2 py-1 text-xs font-medium rounded-full bg-${m.measurementType === 'Baseline' ? 'blue' : m.measurementType === 'Monthly' ? 'green' : 'purple'}-100 text-${m.measurementType === 'Baseline' ? 'blue' : m.measurementType === 'Monthly' ? 'green' : 'purple'}-800">${m.measurementType}</span></td>
            <td>${new Date(m.date).toLocaleDateString() }</td>
            <td><span class="font-mono">${m.weight.toFixed(1)}</span></td>
            <td><span class="font-mono">${m.height.toFixed(1)}</span></td>
            <td><span class="font-mono">${m.bmi.toFixed(1)}</span></td>
            <td><span class="px-2 py-1 text-xs rounded-full status-${m.nutritionalStatus.toLowerCase().replace(' ', '-')} font-medium">${m.nutritionalStatus}</span></td>
            <td>${m.measuredBy}</td>
            <td>
                <button onclick="editMeasurement('${m.id}')" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded">
                    <i data-lucide="edit"></i>
                </button>
                <button onclick="deleteMeasurement('${m.id}')" class="p-1.5 text-red-600 hover:bg-red-50 rounded ml-1">
                    <i data-lucide="trash-2"></i>
                </button>
            </td>
        </tr>
    `).join('') || '<tr><td colspan="9" class="text-center py-12 text-gray-500">No measurements found</td></tr>';
    
    lucide.createIcons();
}

// BMI calculator
function calculateBMI() {
    const weight = parseFloat(document.getElementById('weight').value);
    const height = parseFloat(document.getElementById('height').value);
    if (weight > 0 && height > 0) {
        const bmi = weight / Math.pow(height / 100, 2);
        document.getElementById('bmi').value = bmi.toFixed(1);
    }
}

// Modal
document.getElementById('add-measurement-btn').onclick = () => openMeasurementModal(null);
document.getElementById('measurement-form').onsubmit = saveMeasurement;

function openMeasurementModal(id) {
    // Similar to student modal - populate/edit
    document.getElementById('measurement-modal').classList.remove('hidden');
}

let editingMeasurementId = null;

function openMeasurementModal(id) {
    editingMeasurementId = id;
    document.getElementById('measurement-title').textContent = id ? 'Edit Measurement' : 'Add Measurement';
    document.getElementById('measurement-form').reset();
    document.getElementById('bmi').value = '';
    
    if (id) {
        const measurement = measurements.find(m => m.id === id);
        document.getElementById('measurement-student').value = measurement.studentId;
        document.getElementById('measurement-type').value = measurement.measurementType;
        document.getElementById('measurement-date').value = measurement.date;
        document.getElementById('measured-by').value = measurement.measuredBy;
        document.getElementById('weight').value = measurement.weight;
        document.getElementById('height').value = measurement.height;
        document.getElementById('bmi').value = measurement.bmi.toFixed(1);
        document.getElementById('measurement-remarks').value = measurement.remarks;
    }
    
    document.getElementById('measurement-modal').classList.remove('hidden');
}

function closeMeasurementModal() {
    document.getElementById('measurement-modal').classList.add('hidden');
}

async function saveMeasurement(e) {
    e.preventDefault();
    
    const measurementData = {
        studentId: document.getElementById('measurement-student').value,
        measurementType: document.getElementById('measurement-type').value,
        date: document.getElementById('measurement-date').value,
        measuredBy: document.getElementById('measured-by').value,
        weight: parseFloat(document.getElementById('weight').value),
        height: parseFloat(document.getElementById('height').value),
        remarks: document.getElementById('measurement-remarks').value,
    };
    
    try {
        if (editingMeasurementId) {
            await window.app.measurementsAPI.update(editingMeasurementId, measurementData);
            toast('Measurement updated', 'success');
        } else {
            await window.app.measurementsAPI.create(measurementData);
            toast('Measurement added', 'success');
        }
        loadMeasurements();
        closeMeasurementModal();
    } catch (error) {
        toast(error.message, 'error');
    }
}

async function deleteMeasurement(id) {
    if (confirm('Delete this measurement?')) {
        try {
            await window.app.measurementsAPI.delete(id);
            toast('Measurement deleted', 'success');
            loadMeasurements();
        } catch (error) {
            toast(error.message, 'error');
        }
    }
}

function editMeasurement(id) {
    openMeasurementModal(id);
}


loadMeasurements();
document.getElementById('measurement-search').oninput = renderTable;
document.getElementById('measurement-type-filter').onchange = renderTable;
lucide.createIcons();
</script>

