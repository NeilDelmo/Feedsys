<?php /** Reports - Summary reports with exports */ ?>
<div class="space-y-6">
    <h1 class="text-2xl font-bold">Reports & Analytics</h1>
    
    <!-- Report Filters -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
        <div class="flex flex-col md:flex-row gap-4 items-end justify-between">
            <div class="flex gap-3 flex-1">
                <div>
                    <label class="block text-sm font-medium mb-1.5">Report Type</label>
                    <select id="report-type" class="form-input min-w-[200px]">
                        <option value="nutritional">Nutritional Status Summary</option>
                        <option value="attendance">Attendance Report</option>
                        <option value="progress">Progress Report</option>
                        <option value="beneficiary">Beneficiary Report</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1.5">Date Range</label>
                    <select id="date-range" class="form-input">
                        <option value="all">All Time</option>
                        <option value="month">This Month</option>
                        <option value="quarter">This Quarter</option>
                        <option value="year">This Year</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1.5">Grade</label>
                    <select id="report-grade" class="form-input">
                        <option value="">All Grades</option>
                        <option value="Grade 1">Grade 1</option>
                        <option value="Grade 2">Grade 2</option>
                        <option value="Grade 3">Grade 3</option>
                        <option value="Grade 4">Grade 4</option>
                        <option value="Grade 5">Grade 5</option>
                        <option value="Grade 6">Grade 6</option>
                    </select>
                </div>
            </div>
            <button id="generate-report-btn" class="btn-gradient text-white px-6 py-2.5 rounded-xl font-medium shadow-lg">
                <i data-lucide="refresh-cw" class="w-4 h-4 inline mr-2"></i>
                Generate
            </button>
        </div>
    </div>
    
    <!-- Export Buttons -->
    <div class="flex gap-3">
        <button onclick="exportReportCSV()" class="btn-gradient text-white px-6 py-2.5 rounded-xl font-medium shadow-lg">
            <i data-lucide="download" class="w-4 h-4 inline mr-2"></i>
            Export CSV
        </button>
        <button onclick="exportReportPDF()" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2.5 rounded-xl font-medium shadow-lg">
            <i data-lucide="file-text" class="w-4 h-4 inline mr-2"></i>
            Export PDF
        </button>
    </div>
    
    <!-- Nutritional Status Report -->
    <div id="nutritional-report" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hidden">
        <h2 class="text-lg font-semibold mb-4">Nutritional Status Summary</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="font-medium mb-3">Status Distribution</h3>
                <canvas id="report-pie-chart"></canvas>
            </div>
            <div class="space-y-3">
                <div class="flex justify-between p-3 bg-green-50 rounded-lg">
                    <span class="font-medium">Normal</span>
                    <span id="status-normal" class="font-bold">0</span>
                </div>
                <div class="flex justify-between p-3 bg-red-50 rounded-lg">
                    <span class="font-medium">Underweight</span>
                    <span id="status-underweight" class="font-bold">0</span>
                </div>
                <div class="flex justify-between p-3 bg-orange-50 rounded-lg">
                    <span class="font-medium">Overweight</span>
                    <span id="status-overweight" class="font-bold">0</span>
                </div>
                <div class="flex justify-between p-3 bg-purple-50 rounded-lg">
                    <span class="font-medium">Obese</span>
                    <span id="status-obese" class="font-bold">0</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Attendance Report -->
    <div id="attendance-report" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hidden">
        <h2 class="text-lg font-semibold mb-4">Attendance Summary</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="p-4 bg-blue-50 rounded-lg">
                <p class="text-sm text-gray-600">Total Present</p>
                <p id="attendance-present" class="text-3xl font-bold text-blue-600">0</p>
            </div>
            <div class="p-4 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-600">Total Absent</p>
                <p id="attendance-absent" class="text-3xl font-bold text-gray-600">0</p>
            </div>
            <div class="p-4 bg-green-50 rounded-lg">
                <p class="text-sm text-gray-600">Attendance Rate</p>
                <p id="attendance-rate" class="text-3xl font-bold text-green-600">0%</p>
            </div>
        </div>
        <canvas id="report-bar-chart"></canvas>
    </div>
    
    <!-- Progress Report -->
    <div id="progress-report" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hidden">
        <h2 class="text-lg font-semibold mb-4">Progress Over Time</h2>
        <canvas id="report-line-chart"></canvas>
    </div>
    
    <!-- Beneficiary Report -->
    <div id="beneficiary-report" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hidden">
        <h2 class="text-lg font-semibold mb-4">Beneficiary Summary</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-3">
                <div class="flex justify-between p-3 bg-emerald-50 rounded-lg">
                    <span class="font-medium">Total Beneficiaries</span>
                    <span id="beneficiary-count" class="font-bold">0</span>
                </div>
                <div class="flex justify-between p-3 bg-blue-50 rounded-lg">
                    <span class="font-medium">Beneficiaries with Allergy</span>
                    <span id="beneficiary-allergy" class="font-bold">0</span>
                </div>
            </div>
            <div>
                <h3 class="font-medium mb-3">By Grade</h3>
                <div id="beneficiary-by-grade" class="space-y-2">
                    <!-- Dynamically filled -->
                </div>
            </div>
        </div>
    </div>
    
    <!-- Data Table (used for exports) -->
    <div id="report-table" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full data-table">
                <thead>
                    <tr id="report-table-header"></tr>
                </thead>
                <tbody id="report-table-body">
                    <tr><td colspan="10" class="text-center py-8 text-gray-500">Select report type and click Generate</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.getElementById('page-title').textContent = 'Reports & Analytics';
let reportData = [];
let currentReportType = 'nutritional';
let reportCharts = {};

// Generate report
document.getElementById('generate-report-btn').onclick = generateReport;
document.getElementById('report-type').onchange = () => {
    currentReportType = document.getElementById('report-type').value;
    generateReport();
};

async function generateReport() {
    try {
        const reportType = document.getElementById('report-type').value;
        const dateRange = document.getElementById('date-range').value;
        const grade = document.getElementById('report-grade').value;
        
        const [students, measurements, attendance] = await Promise.all([
            window.app.studentsAPI.getAll(),
            window.app.measurementsAPI.getAll(),
            window.app.attendanceAPI.getAll()
        ]);
        
        // Filter data
        let filteredStudents = students;
        if (grade) {
            filteredStudents = students.filter(s => s.grade === grade);
        }
        
        // Hide all reports
        document.getElementById('nutritional-report').classList.add('hidden');
        document.getElementById('attendance-report').classList.add('hidden');
        document.getElementById('progress-report').classList.add('hidden');
        document.getElementById('beneficiary-report').classList.add('hidden');
        
        switch(reportType) {
            case 'nutritional':
                generateNutritionalReport(measurements, filteredStudents);
                break;
            case 'attendance':
                generateAttendanceReport(attendance, filteredStudents);
                break;
            case 'progress':
                generateProgressReport(measurements, filteredStudents);
                break;
            case 'beneficiary':
                generateBeneficiaryReport(students, measurements);
                break;
        }
        
        lucide.createIcons();
    } catch (error) {
        toast('Failed to generate report: ' + error.message, 'error');
    }
}

function generateNutritionalReport(measurements, students) {
    const latestMeasurements = new Map();
    measurements.forEach(m => {
        const existing = latestMeasurements.get(m.studentId);
        if (!existing || new Date(m.date) > new Date(measurements.find(x => x.id === existing)?.date || 0)) {
            latestMeasurements.set(m.studentId, m.nutritionalStatus);
        }
    });
    
    const counts = { underweight: 0, normal: 0, overweight: 0, obese: 0 };
    const tableData = [];
    
    students.forEach(student => {
        const latestStatus = latestMeasurements.get(student.id) || 'Unknown';
        const latestMeasurement = measurements.filter(m => m.studentId === student.id).sort((a, b) => new Date(b.date) - new Date(a.date))[0];
        
        if (latestStatus.includes('Underweight')) counts.underweight++;
        else if (latestStatus === 'Normal') counts.normal++;
        else if (latestStatus === 'Overweight') counts.overweight++;
        else if (latestStatus === 'Obese') counts.obese++;
        
        tableData.push({
            studentId: student.studentId,
            name: student.fullName,
            grade: student.grade,
            bmi: latestMeasurement?.bmi || 'N/A',
            status: latestStatus,
            date: latestMeasurement?.date || 'N/A'
        });
    });
    
    document.getElementById('status-normal').textContent = counts.normal;
    document.getElementById('status-underweight').textContent = counts.underweight;
    document.getElementById('status-overweight').textContent = counts.overweight;
    document.getElementById('status-obese').textContent = counts.obese;
    
    // Pie chart
    const ctx = document.getElementById('report-pie-chart').getContext('2d');
    if (reportCharts.pie) reportCharts.pie.destroy();
    reportCharts.pie = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Normal', 'Underweight', 'Overweight', 'Obese'],
            datasets: [{
                data: [counts.normal, counts.underweight, counts.overweight, counts.obese],
                backgroundColor: ['#16a34a', '#dc2626', '#f59e0b', '#8b5cf6'],
            }]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });
    
    renderReportTable(tableData, ['studentId', 'name', 'grade', 'bmi', 'status', 'date']);
    reportData = tableData;
    document.getElementById('nutritional-report').classList.remove('hidden');
}

function generateAttendanceReport(attendance, students) {
    const studentIds = students.map(s => s.id);
    const filtered = attendance.filter(a => studentIds.includes(a.studentId.toString()));
    
    let presentCount = 0, absentCount = 0;
    const gradeAttendance = {};
    
    filtered.forEach(a => {
        if (a.present) presentCount++;
        else absentCount++;
    });
    
    const total = presentCount + absentCount;
    const rate = total > 0 ? ((presentCount / total) * 100).toFixed(1) : 0;
    
    document.getElementById('attendance-present').textContent = presentCount;
    document.getElementById('attendance-absent').textContent = absentCount;
    document.getElementById('attendance-rate').textContent = rate + '%';
    
    // Bar chart
    const ctx = document.getElementById('report-bar-chart').getContext('2d');
    if (reportCharts.bar) reportCharts.bar.destroy();
    reportCharts.bar = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Present', 'Absent'],
            datasets: [{
                label: 'Count',
                data: [presentCount, absentCount],
                backgroundColor: ['#16a34a', '#dc2626']
            }]
        },
        options: { responsive: true, scales: { y: { beginAtZero: true } } }
    });
    
    const tableData = filtered.map(a => ({
        studentName: a.studentName,
        date: a.date,
        present: a.present ? 'Yes' : 'No',
        mealReceived: a.mealReceived ? 'Yes' : 'No',
        remarks: a.remarks || '-'
    }));
    
    renderReportTable(tableData, ['studentName', 'date', 'present', 'mealReceived', 'remarks']);
    reportData = tableData;
    document.getElementById('attendance-report').classList.remove('hidden');
}

function generateProgressReport(measurements, students) {
    // Group by month
    const monthlyData = {};
    measurements.forEach(m => {
        const date = new Date(m.date);
        const month = date.toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
        if (!monthlyData[month]) monthlyData[month] = { underweight: 0, total: 0 };
        
        const latestStatus = m.nutritionalStatus;
        if (latestStatus.includes('Underweight')) monthlyData[month].underweight++;
        monthlyData[month].total++;
    });
    
    const labels = Object.keys(monthlyData).sort();
    const data = labels.map(m => monthlyData[m].underweight);
    
    const ctx = document.getElementById('report-line-chart').getContext('2d');
    if (reportCharts.line) reportCharts.line.destroy();
    reportCharts.line = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Underweight Count',
                data: data,
                borderColor: '#dc2626',
                backgroundColor: 'rgba(220, 38, 38, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: { responsive: true, scales: { y: { beginAtZero: true } } }
    });
    
    const tableData = labels.map((label, i) => ({
        period: label,
        underweightCount: data[i],
        totalMeasurements: monthlyData[label].total
    }));
    
    renderReportTable(tableData, ['period', 'underweightCount', 'totalMeasurements']);
    reportData = tableData;
    document.getElementById('progress-report').classList.remove('hidden');
}

function generateBeneficiaryReport(students, measurements) {
    const beneficiaries = students.filter(s => s.beneficiary);
    const withAllergy = beneficiaries.filter(s => s.hasAllergy).length;
    
    const byGrade = {};
    beneficiaries.forEach(b => {
        if (!byGrade[b.grade]) byGrade[b.grade] = 0;
        byGrade[b.grade]++;
    });
    
    document.getElementById('beneficiary-count').textContent = beneficiaries.length;
    document.getElementById('beneficiary-allergy').textContent = withAllergy;
    
    const gradeHtml = Object.entries(byGrade).map(([grade, count]) => 
        `<div class="flex justify-between p-2"><span>${grade}</span><span class="font-bold">${count}</span></div>`
    ).join('');
    document.getElementById('beneficiary-by-grade').innerHTML = gradeHtml;
    
    const tableData = beneficiaries.map(b => ({
        studentId: b.studentId,
        name: b.fullName,
        grade: b.grade,
        section: b.section,
        hasAllergy: b.hasAllergy ? 'Yes' : 'No',
        allergyNotes: b.allergyNotes || '-'
    }));
    
    renderReportTable(tableData, ['studentId', 'name', 'grade', 'section', 'hasAllergy', 'allergyNotes']);
    reportData = tableData;
    document.getElementById('beneficiary-report').classList.remove('hidden');
}

function renderReportTable(data, columns) {
    const header = document.getElementById('report-table-header');
    const body = document.getElementById('report-table-body');
    
    header.innerHTML = columns.map(col => `<th>${col.replace(/([A-Z])/g, ' $1').trim()}</th>`).join('');
    body.innerHTML = data.map(row => 
        `<tr>${columns.map(col => `<td>${row[col] || '-'}</td>`).join('')}</tr>`
    ).join('');
}

function exportReportCSV() {
    if (!reportData || reportData.length === 0) {
        toast('Please generate a report first', 'error');
        return;
    }
    window.app.exportCSV(reportData, `report-${currentReportType}-${new Date().toISOString().split('T')[0]}.csv`);
}

function exportReportPDF() {
    if (!reportData || reportData.length === 0) {
        toast('Please generate a report first', 'error');
        return;
    }
    window.app.exportPDF(reportData, `report-${currentReportType}-${new Date().toISOString().split('T')[0]}.pdf`, `${currentReportType} Report`);
}

// Init - load default report
generateReport();
lucide.createIcons();
</script>
