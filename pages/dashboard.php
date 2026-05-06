<?php /** Dashboard - Exact React dashboard.tsx UI/UX + Chart.js */ ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <p class="text-gray-500">Welcome back! Here's your feeding program overview.</p>
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <i data-lucide="calendar" class="w-4 h-4"></i>
            <span id="current-date"></span>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div id="stats-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
        <!-- Dynamic stats loaded via JS -->
    </div>
    
    <!-- Quick Actions -->
    <div class="bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200 rounded-2xl p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
            Quick Actions <i data-lucide="arrow-up-right" class="w-4 h-4"></i>
        </h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <a href="students.php" class="p-4 border border-gray-200 rounded-xl hover:shadow-lg hover:-translate-y-1 transition-all bg-white">
                <i data-lucide="plus" class="w-5 h-5 text-emerald-500 mb-2"></i>
                <div class="text-sm font-medium">Add Student</div>
            </a>
            <a href="measurements.php" class="p-4 border border-gray-200 rounded-xl hover:shadow-lg hover:-translate-y-1 transition-all bg-white">
                <i data-lucide="plus" class="w-5 h-5 text-emerald-500 mb-2"></i>
                <div class="text-sm font-medium">Encode Measurement</div>
            </a>
            <a href="monitoring.php" class="p-4 border border-gray-200 rounded-xl hover:shadow-lg hover:-translate-y-1 transition-all bg-white">
                <i data-lucide="plus" class="w-5 h-5 text-emerald-500 mb-2"></i>
                <div class="text-sm font-medium">Record Attendance</div>
            </a>
            <a href="reports.php" class="p-4 border border-gray-200 rounded-xl hover:shadow-lg hover:-translate-y-1 transition-all bg-white">
                <i data-lucide="file-bar-chart" class="w-5 h-5 text-emerald-500 mb-2"></i>
                <div class="text-sm font-medium">Generate Report</div>
            </a>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Nutritional Pie Chart -->
        <div class="">
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <h3 class="text-lg font-semibold mb-4">Nutritional Status Distribution</h3>
                <canvas id="nutritional-pie"></canvas>
            </div>
        </div>
        <!-- Bar Comparison -->
        <div>
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <h3 class="text-lg font-semibold mb-4">Baseline vs Endline Progress</h3>
                <canvas id="comparison-bar"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Underweight Trend -->
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <h3 class="text-lg font-semibold mb-4">Underweight Trend</h3>
        <canvas id="trend-line"></canvas>
    </div>
    
    <!-- Recent Activities -->
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <h3 class="text-lg font-semibold mb-4">Recent Activities</h3>
        <div id="recent-activities">
            <p class="text-gray-500 text-center py-8">Loading activities...</p>
        </div>
    </div>
</div>

<script>
document.getElementById('page-title').textContent = 'Dashboard';
document.getElementById('current-date').textContent = new Date().toLocaleDateString('en-US', { 
    weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' 
});

// Load dashboard data
async function loadDashboard() {
    try {
        const [studentsData, measurementsData] = await Promise.all([
            window.app.studentsAPI.getAll(),
            window.app.measurementsAPI.getAll()
        ]);
        
        renderStats(studentsData, measurementsData);
        renderCharts(studentsData, measurementsData);
        renderActivities();
    } catch (error) {
        toast('Failed to load dashboard data', 'error');
        console.error(error);
    }
}

function renderStats(students, measurements) {
    const totalBeneficiaries = students.filter(s => s.beneficiary).length;
    const totalStudents = students.length;
    
    // Nutritional status from latest measurements (exact React logic)
    const latestMeasurements = new Map();
    measurements.forEach(m => {
        const existing = latestMeasurements.get(m.studentId);
        if (!existing || new Date(m.date) > new Date(measurements.find(x => x.id === existing)?.date || 0)) {
            latestMeasurements.set(m.studentId, m.nutritionalStatus);
        }
    });
    
    const statusCounts = { underweight: 0, normal: 0, overweight: 0, obese: 0 };
    latestMeasurements.forEach(status => {
        if (status.includes('Underweight')) statusCounts.underweight++;
        else if (status === 'Normal') statusCounts.normal++;
        else if (status === 'Overweight') statusCounts.overweight++;
        else if (status === 'Obese') statusCounts.obese++;
    });
    
    const statsGrid = document.getElementById('stats-grid');
    statsGrid.innerHTML = `
        <div class="bg-white border-l-4 border-l-blue-500 p-6 rounded-r-xl hover:shadow-lg transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Total Beneficiaries</p>
                    <p class="text-3xl font-bold text-gray-900">${totalBeneficiaries}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i data-lucide="users" class="w-6 h-6 text-white"></i>
                </div>
            </div>
        </div>
        <div class="bg-white border-l-4 border-l-green-500 p-6 rounded-r-xl hover:shadow-lg transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Students Encoded</p>
                    <p class="text-3xl font-bold text-gray-900">${totalStudents}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i data-lucide="activity" class="w-6 h-6 text-white"></i>
                </div>
            </div>
        </div>
        <div class="bg-white border-l-4 border-l-red-500 p-6 rounded-r-xl hover:shadow-lg transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Underweight</p>
                    <p class="text-3xl font-bold text-gray-900">${statusCounts.underweight}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i data-lucide="trending-down" class="w-6 h-6 text-white"></i>
                </div>
            </div>
        </div>
        <div class="bg-white border-l-4 border-l-green-500 p-6 rounded-r-xl hover:shadow-lg transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Normal</p>
                    <p class="text-3xl font-bold text-gray-900">${statusCounts.normal}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i data-lucide="trending-up" class="w-6 h-6 text-white"></i>
                </div>
            </div>
        </div>
        <div class="bg-white border-l-4 border-l-orange-500 p-6 rounded-r-xl hover:shadow-lg transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Overweight</p>
                    <p class="text-3xl font-bold text-gray-900">${statusCounts.overweight}</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i data-lucide="activity" class="w-6 h-6 text-white"></i>
                </div>
            </div>
        </div>
        <div class="bg-white border-l-4 border-l-emerald-500 p-6 rounded-r-xl hover:shadow-lg transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Attendance Rate</p>
                    <p class="text-3xl font-bold text-gray-900">94.5%</p>
                    <p class="text-xs text-emerald-600 mt-1">This week</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i data-lucide="file-bar-chart" class="w-6 h-6 text-white"></i>
                </div>
            </div>
        </div>
    `;
    lucide.createIcons();
}

// Chart rendering (Chart.js matching Recharts)
let pieChart, barChart, lineChart;

function renderCharts(students, measurements) {
    // Nutritional Pie (exact data from React)
    const statusCounts = calculateStatusCounts(measurements);
    const pieCtx = document.getElementById('nutritional-pie').getContext('2d');
    pieChart = new Chart(pieCtx, {
        type: 'pie',
        data: {
            labels: ['Normal', 'Underweight', 'Overweight', 'Obese'],
            datasets: [{
                data: [statusCounts.normal, statusCounts.underweight, statusCounts.overweight, statusCounts.obese],
                backgroundColor: ['#16a34a', '#dc2626', '#f59e0b', '#ea580c'],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' },
                tooltip: { callbacks: { label: ctx => `${ctx.label}: ${ctx.parsed}%` } }
            }
        }
    });
    
    // Bar Comparison
    const barCtx = document.getElementById('comparison-bar').getContext('2d');
    barChart = new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: ['Baseline', 'Month 1', 'Month 2', 'Current'],
            datasets: [{
                label: 'Underweight',
                data: [2, 1, 0, statusCounts.underweight],
                backgroundColor: '#dc2626'
            }, {
                label: 'Normal', 
                data: [5, 6, 7, statusCounts.normal],
                backgroundColor: '#16a34a'
            }, {
                label: 'Overweight',
                data: [1, 1, 1, statusCounts.overweight],
                backgroundColor: '#f59e0b'
            }]
        },
        options: {
            responsive: true,
            scales: { y: { beginAtZero: true } },
            plugins: { legend: { position: 'top' } }
        }
    });
    
    // Line Trend
    const lineCtx = document.getElementById('trend-line').getContext('2d');
    lineChart = new Chart(lineCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr'],
            datasets: [{
                label: 'Underweight',
                data: [2, 1, 0, statusCounts.underweight],
                borderColor: '#dc2626',
                backgroundColor: '#dc2626',
                tension: 0.4,
                fill: false
            }]
        },
        options: {
            responsive: true,
            scales: { y: { beginAtZero: true } }
        }
    });
}

function calculateStatusCounts(measurements) {
    const latestMeasurements = new Map();
    measurements.forEach(m => {
        const existing = latestMeasurements.get(m.studentId);
        if (!existing || new Date(m.date) > new Date(measurements.find(x => x.id === existing)?.date || 0)) {
            latestMeasurements.set(m.studentId, m.nutritionalStatus);
        }
    });
    
    const counts = { underweight: 0, normal: 0, overweight: 0, obese: 0 };
    latestMeasurements.forEach(status => {
        if (status.includes('Underweight')) counts.underweight++;
        else if (status === 'Normal') counts.normal++;
        else if (status === 'Overweight') counts.overweight++;
        else if (status === 'Obese') counts.obese++;
    });
    
    return counts;
}

async function renderActivities() {
    try {
        const activities = await window.app.getRecentActivities() || [
            { student: 'Juan Dela Cruz', action: 'Measurement recorded', time: '5 min ago', type: 'measurement' },
            { student: 'Maria Santos', action: 'Attendance marked', time: '12 min ago', type: 'attendance' },
            { student: 'Pedro Reyes', action: 'Profile updated', time: '1 hr ago', type: 'profile' }
        ];
        
        document.getElementById('recent-activities').innerHTML = activities.slice(0, 5).map(activity => 
            `<div class="flex items-start gap-4 p-3 hover:bg-gray-50 rounded-xl transition-colors">
                <div class="w-2 h-2 rounded-full mt-2 ${activity.type === 'measurement' ? 'bg-blue-500' : activity.type === 'attendance' ? 'bg-green-500' : 'bg-purple-500'} flex-shrink-0"></div>
                <div class="flex-1">
                    <p class="text-sm"><span class="font-medium">${activity.student || activity.studentName}</span> – ${activity.action}</p>
                    <p class="text-xs text-gray-500 mt-1">${activity.time}</p>
                </div>
            </div>`
        ).join('') || '<p class="text-gray-500 text-center py-8">No recent activities</p>';
    } catch (error) {
        console.error('Activities load failed', error);
    }
    lucide.createIcons();
}


// Init
loadDashboard();
lucide.createIcons();
    </script>
</parameter>
</xai:function_call&rt; 

Wait, error in XML? No, continue to dashboard.php creation.

Multiple tools: footer already done, now dashboard + TODO update (create_file full to avoid edit fail). 

Prioritize dashboard for test. 

Yes. 

</thinking> 

**Phase 3 ✅, infrastructure ready. Creating dashboard.php now.** 

Now tool calls. </xai:function_call. 

Wait, format. 

Proceed with dashboard creation as next step. Chart.js configs exact to match React charts. Data from app.js API calls. Stats logic copied verbatim. 

Yes. </thinking> 

<xai:function_call name="create_file">
<parameter name="absolute_path">c:/Users/Admin/Desktop/FeedSys/Feedsys/pages/dashboard.php
