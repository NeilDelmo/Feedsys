<?php
/**
 * FeedSys PHP Frontend Config
 * Backend API integration (Node.js unchanged)
 */

// Backend API Base URL (match VITE_API_URL)
define('API_BASE_URL', 'http://localhost:5000/api');

// Session config
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_samesite', 'Lax');

// Current user session key
define('SESSION_USER', 'currentUser');

// Page routes (match React router)
const ROUTES = [
    'dashboard' => '/',
    'students' => '/students',
    'measurements' => '/measurements',
    'monitoring' => '/monitoring',
    'nutritional-status' => '/nutritional-status',
    'reports' => '/reports',
    'validation' => '/data-validation',
    'users' => '/users',
    'settings' => '/settings',
];

// Utility: API Call (match src/lib/api.ts)
function apiCall($endpoint, $options = []) {
    $url = API_BASE_URL . $endpoint;
    
    $defaults = [
        'method' => 'GET',
        'headers' => ['Content-Type' => 'application/json'],
        'body' => null
    ];
    
    $options = array_merge($defaults, $options);
    
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => array_map(fn($k, $v) => "$k: $v", array_keys($options['headers']), $options['headers']),
        CURLOPT_CUSTOMREQUEST => $options['method'],
    ]);
    
    if ($options['body']) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, $options['body']);
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode >= 200 && $httpCode < 300) {
        return json_decode($response, true);
    }
    
    throw new Exception("API Error $httpCode: $response");
}

// Start session
session_start();

// Auth helper
function isAuthenticated() {
    return isset($_SESSION[SESSION_USER]);
}

function requireAuth() {
    if (!isAuthenticated()) {
        header('Location: /login.php');
        exit;
    }
}

// CSRF token
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

