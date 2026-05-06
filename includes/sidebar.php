<aside id="sidebar" class="fixed top-0 left-0 z-40 h-screen bg-white border-r border-gray-200 shadow-lg transition-all duration-300" style="width: 256px;">
    <div class="flex flex-col h-full">
        <!-- Logo -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200">
            <div class="flex items-center gap-2">
                <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center shadow-md">
                    <i data-lucide="utensils" class="w-6 h-6 text-white"></i>
                </div>
                <div>
                    <div class="text-lg font-semibold bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">FEED</div>
                    <div class="text-xs text-gray-500">System</div>
                </div>
            </div>
            <button id="sidebar-toggle" class="text-gray-500 hover:text-gray-900">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        
        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto p-4 space-y-1">
            <a href="/" class="nav-item active flex items-center gap-3 px-3 py-2.5 rounded-lg bg-gradient-to-r from-emerald-500 to-teal-600 text-white shadow-md">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i><span>Dashboard</span>
            </a>
            <a href="students.php" class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-100 hover:text-gray-900">
                <i data-lucide="users" class="w-5 h-5"></i><span>Student Profiles</span>
            </a>
            <a href="measurements.php" class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-100 hover:text-gray-900">
                <i data-lucide="ruler" class="w-5 h-5"></i><span>Measurements</span>
            </a>
            <a href="monitoring.php" class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-100 hover:text-gray-900">
                <i data-lucide="utensils" class="w-5 h-5"></i><span>Feeding Monitoring</span>
            </a>
            <a href="nutritional-status.php" class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-100 hover:text-gray-900">
                <i data-lucide="activity" class="w-5 h-5"></i><span>Nutritional Status</span>
            </a>
            <a href="reports.php" class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-100 hover:text-gray-900">
                <i data-lucide="file-text" class="w-5 h-5"></i><span>Reports</span>
            </a>
            <a href="validation.php" class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-100 hover:text-gray-900">
                <i data-lucide="check-square" class="w-5 h-5"></i><span>Data Validation</span>
            </a>
            <a href="users.php" class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-100 hover:text-gray-900">
                <i data-lucide="user-cog" class="w-5 h-5"></i><span>User Management</span>
            </a>
            <a href="settings.php" class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-100 hover:text-gray-900">
                <i data-lucide="settings" class="w-5 h-5"></i><span>Settings</span>
            </a>
        </nav>
        
        <!-- User Profile Footer -->
        <div class="p-4 border-t border-gray-200">
            <div class="flex items-center gap-3 p-2.5 rounded-lg hover:bg-gray-100 cursor-pointer" onclick="toggleUserMenu()">
                <div id="sidebar-user-avatar" class="w-8 h-8 rounded-full bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white text-sm font-medium">U</div>
                <div class="flex-1 min-w-0">
                    <div id="sidebar-user-name" class="text-sm font-medium text-gray-900 truncate">User</div>
                    <div id="sidebar-user-role" class="text-xs text-gray-500">Role</div>
                </div>
                <i data-lucide="chevron-down" class="w-4 h-4 text-gray-500"></i>
            </div>
        </div>
    </div >
</aside>
<script>
lucide.createIcons();
</script>

