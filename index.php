<?php
require_once 'config.php';

// Simple PHP Router (match React routes.tsx exactly)
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// Strip the leading slash and any extension if provided
$page = trim(basename($path), '/') ?: 'dashboard';
$page = str_replace('.php', '', $page);

// Auth guard (protect all except login)
if ($page !== 'login' && !isAuthenticated()) {
    header('Location: /login.php');
    exit;
}

// Protected pages map
$protectedPages = [
    'dashboard'          => 'pages/dashboard.php',
    'students'           => 'pages/students.php',
    'measurements'       => 'pages/measurements.php',
    'monitoring'         => 'pages/monitoring.php',
    'nutritional-status' => 'pages/nutritional-status.php',
    'reports'            => 'pages/reports.php',
    'validation'         => 'pages/validation.php',
    'users'              => 'pages/users.php',
    'settings'           => 'pages/settings.php',
];

$targetPage = $protectedPages[$page] ?? 'pages/dashboard.php';

if (file_exists($targetPage)) {
    include 'includes/header.php';  // Topbar
    include 'includes/sidebar.php'; // Sidebar nav  
    include $targetPage;            // Page content
    include 'includes/footer.php';  // Scripts
} else {
    http_response_code(404);
    echo "<h1>Page Not Found</h1>";
    echo "<p>Could not find the page: " . htmlspecialchars($page) . "</p>";
}
?>
