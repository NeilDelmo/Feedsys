<?php
require_once 'config.php';

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}

// Handle AJAX login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    $input = json_decode(file_get_contents('php://input'), true);
    $username = $input['username'] ?? '';
    $password = $input['password'] ?? '';
    
    try {
        $response = apiCall('/auth/login', [
            'method' => 'POST',
            'body' => json_encode(['username' => $username, 'password' => $password])
        ]);
        
        if (isset($response['success']) && $response['success'] && isset($response['user'])) {
            $_SESSION[SESSION_USER] = [
                'id' => $response['user']['id'],
                'username' => $response['user']['username'],
                'fullName' => $response['user']['full_name'],
                'role' => $response['user']['role'],
                'email' => $response['user']['email']
            ];
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Invalid credentials']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | FEED System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        'gradient': 'gradient 3s ease-in-out infinite',
                    },
                    keyframes: {
                        gradient: {
                            '0%, 100%': { backgroundPosition: '0% 50%' },
                            '50%': { backgroundPosition: '100% 50%' },
                        },
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen bg-gradient-to-br from-emerald-50 via-green-50 to-teal-50 flex items-center justify-center p-4 relative overflow-hidden">
    <!-- Animated BGs -->
    <div class="absolute top-20 left-20 w-72 h-72 bg-emerald-200/30 rounded-full blur-3xl animate-pulse"></div>
    <div class="absolute bottom-20 right-20 w-96 h-96 bg-teal-200/30 rounded-full blur-3xl animate-pulse"></div>
    
    <div class="w-full max-w-md relative z-10">
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20 hover:shadow-3xl transition-shadow">
            <!-- Logo -->
            <div class="text-center mb-8">
                <div class="w-20 h-20 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-3xl flex items-center justify-center mx-auto mb-4 shadow-lg hover:rotate-12 hover:scale-110 transition-all duration-500">
                    <i data-lucide="utensils" class="w-11 h-11 text-white"></i>
                </div>
                <h1 class="text-4xl font-bold text-gray-900 mb-2 flex items-center justify-center gap-2">
                    FEED System <i data-lucide="sparkles" class="w-6 h-6 text-emerald-500"></i>
                </h1>
                <p class="text-sm text-gray-500">Feeding Encoding, Evaluation, and Data Management System</p>
            </div>
            
            <!-- Error -->
            <div id="error-msg" class="hidden mb-6 bg-red-50 border border-red-200 rounded-xl p-4 flex gap-3">
                <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 mt-0.5 flex-shrink-0"></i>
                <p id="error-text" class="text-sm text-red-800"></p>
            </div>
            
            <!-- Login Form -->
            <form id="login-form" class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Username</label>
                    <input type="text" id="username" placeholder="Enter your username" required
                           class="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                    <div class="relative">
                        <input type="password" id="password" placeholder="Enter your password" required
                               class="w-full px-3.5 py-2.5 pr-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <button type="button" id="toggle-password" class="absolute right-3 top-1/2 -translate-y-1/2 p-1 hover:bg-gray-100 rounded-lg">
                            <i data-lucide="eye-off" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>
                
                <button type="submit" id="login-btn" 
                        class="w-full bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-medium py-3 px-4 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 text-lg">
                    <span id="login-text">Sign In</span>
                    <div id="login-spinner" class="hidden w-6 h-6 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                </button>
            </form>
            
            <!-- Forgot Password -->
            <div class="mt-4 text-center">
                <a href="#" class="text-sm text-emerald-600 hover:text-emerald-700 hover:underline">Forgot password?</a>
            </div>
            
            <!-- Demo Credentials -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <p class="text-xs text-gray-500 text-center mb-3 flex items-center justify-center gap-1">
                    <i data-lucide="sparkles" class="w-3 h-3"></i>Demo Credentials
                </p>
                <div class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl p-4 text-xs space-y-2">
                    <div class="flex justify-between items-center py-1">
                        <span class="text-gray-600">Admin:</span>
                        <span class="font-mono text-gray-900 bg-white px-2 py-1 rounded font-medium">admin / admin123</span>
                    </div>
                    <div class="flex justify-between items-center py-1">
                        <span class="text-gray-600">Encoder:</span>
                        <span class="font-mono text-gray-900 bg-white px-2 py-1 rounded font-medium">encoder / encoder123</span>
                    </div>
                </div>
            </div>
        </div>
        
        <p class="text-center text-sm text-gray-600 mt-6">
            Nasugbu West Central School - FEED System © 2024. All rights reserved.
        </p>
    </div>
    
    <script>
        lucide.createIcons();
        
        const form = document.getElementById('login-form');
        const errorMsg = document.getElementById('error-msg');
        const errorText = document.getElementById('error-text');
        const loginBtn = document.getElementById('login-btn');
        const loginText = document.getElementById('login-text');
        const loginSpinner = document.getElementById('login-spinner');
        const togglePassword = document.getElementById('toggle-password');
        let showPassword = false;
        
        togglePassword.addEventListener('click', () => {
            showPassword = !showPassword;
            const passwordInput = document.getElementById('password');
            passwordInput.type = showPassword ? 'text' : 'password';
            lucide.createIcons();
        });
        
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value.trim();
            
            if (!username || !password) {
                showError('Please fill in all required fields.');
                return;
            }
            
            loginBtn.disabled = true;
            loginText.classList.add('hidden');
            loginSpinner.classList.remove('hidden');
            errorMsg.classList.add('hidden');
            
            try {
                const response = await fetch('login.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ username, password })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    window.location.href = '/';
                } else {
                    showError(data.error || 'Login failed. Please try again.');
                }
            } catch (error) {
                showError('Connection error. Please check if backend is running.');
                console.error('Login error:', error);
            } finally {
                loginBtn.disabled = false;
                loginText.classList.remove('hidden');
                loginSpinner.classList.add('hidden');
            }
        });
        
        function showError(message) {
            errorText.textContent = message;
            errorMsg.classList.remove('hidden');
            lucide.createIcons();
        }
    </script>
</body>
</html>

