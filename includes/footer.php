        </main>
    </div>
    
    <script>
        // Global JS init (sidebar toggle, user data, lucide icons)
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
            
            // Load current user data via AJAX
            loadCurrentUser();
            
            // Sidebar toggle
            document.getElementById('sidebar-toggle').addEventListener('click', function() {
                toggleSidebar();
            });
            
            document.getElementById('mobile-menu-btn')?.addEventListener('click', toggleSidebar);
        });
        
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            sidebar.classList.toggle('collapsed');
            mainContent.style.marginLeft = sidebar.classList.contains('collapsed') ? '80px' : '256px';
        }
        
        function toggleUserMenu() {
            document.getElementById('user-menu').classList.toggle('hidden');
        }
        
        function loadCurrentUser() {
            // Mock - replace with API call when auth ready
            const user = <?php echo json_encode($_SESSION[SESSION_USER] ?? ['fullName' => 'Admin User', 'role' => 'Administrator']); ?>;
            document.getElementById('user-avatar').textContent = user.fullName.charAt(0);
            document.getElementById('user-name').textContent = user.fullName;
            document.getElementById('sidebar-user-avatar').textContent = user.fullName.charAt(0);
            document.getElementById('sidebar-user-name').textContent = user.fullName;
            document.getElementById('sidebar-user-role').textContent = user.role;
        }
        
        // Logout handler
        document.querySelector('[href="?logout=1"]')?.addEventListener('click', function(e) {
            e.preventDefault();
            if (confirm('Log out?')) {
                window.location.href = '/login.php?logout=1';
            }
        });
    </script>
</body>
</html>

