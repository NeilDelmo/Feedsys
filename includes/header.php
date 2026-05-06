<?php /** Topbar from dashboard-layout.tsx - UI/UX preserved */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FEED System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="public/js/app-enhanced.js" defer></script>
<script src="public/js/app.js" defer></script> <!-- fallback -->
    <link rel="stylesheet" href="public/css/app.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        'spin-slow': 'spin 3s linear infinite',
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>
    
    <!-- Main Content -->
    <div id="main-content" class="min-h-screen transition-all duration-300" style="margin-left: 256px;">
        <!-- Topbar -->
        <header class="sticky top-0 z-30 bg-white/80 backdrop-blur-md border-b border-gray-200 shadow-sm">
            <div class="flex items-center justify-between px-6 py-4">
                <div class="flex items-center gap-4">
                    <button id="mobile-menu-btn" class="lg:hidden p-2 rounded-lg hover:bg-gray-100">
                        <i data-lucide="menu" class="w-5 h-5"></i>
                    </button>
                    <div>
                        <h2 id="page-title" class="text-xl font-semibold text-gray-900">Dashboard</h2>
                        <p class="text-sm text-gray-500"><?php echo date('l, F j'); ?></p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <button class="relative p-2 rounded-lg hover:bg-gray-100 transition-colors">
                        <i data-lucide="bell" class="w-5 h-5 text-gray-500"></i>
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>
                    <div class="flex items-center gap-2 p-2 hover:bg-gray-100 rounded-lg cursor-pointer" onclick="toggleUserMenu()">
                        <div id="user-avatar" class="w-8 h-8 rounded-full bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white text-sm font-medium">U</div>
                        <span id="user-name" class="hidden md:block font-medium"></span>
                        <i data-lucide="chevron-down" class="w-4 h-4 text-gray-500"></i>
                    </div>
                </div>
            </div>
            <!-- User Menu -->
            <div id="user-menu" class="hidden absolute right-6 top-16 bg-white border border-gray-200 rounded-lg shadow-lg">
                <a href="settings.php" class="flex items-center gap-2 px-4 py-2.5 hover:bg-gray-100 text-sm">
                    <i data-lucide="user"></i> Profile Settings
                </a>
                <a href="?logout=1" class="flex items-center gap-2 px-4 py-2.5 hover:bg-red-50 text-sm text-red-600">
                    <i data-lucide="log-out"></i> Log Out
                </a>
            </div>
        </header>
        
        <!-- Page Content -->
        <main class="p-6">

