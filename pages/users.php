<?php /** Users - User management CRUD */ ?>
<div class="space-y-6">
    <h1 class="text-2xl font-bold">User Management</h1>
    
    <!-- Search/Filter -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
        <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="flex gap-3 flex-1 max-w-md">
                <input id="user-search" placeholder="Search users..." class="flex-1 form-input">
                <select id="role-filter" class="form-input">
                    <option value="">All Roles</option>
                    <option value="admin">Admin</option>
                    <option value="teacher">Teacher</option>
                    <option value="staff">Staff</option>
                </select>
            </div>
            <button id="add-user-btn" class="btn-gradient text-white px-6 py-2.5 rounded-xl font-medium shadow-lg">
                <i data-lucide="user-plus" class="w-4 h-4 inline mr-2"></i>
                Add User
            </button>
        </div>
    </div>
    
    <!-- Users Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table id="users-table" class="w-full data-table">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="7" class="text-center py-8 text-gray-500">Loading users...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add/Edit User Modal -->
<div id="user-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50" onclick="closeUserModal()">
    <div class="w-full max-w-2xl mx-auto mt-20 p-1" onclick="event.stopPropagation()">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 id="user-modal-title" class="text-xl font-bold">Add User</h2>
                    <button onclick="closeUserModal()" class="p-2 hover:bg-gray-100 rounded-lg">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <form id="user-form" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1.5">Username *</label>
                            <input id="user-username" required class="form-input">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1.5">Email *</label>
                            <input id="user-email" type="email" required class="form-input">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1.5">Full Name *</label>
                        <input id="user-full-name" required class="form-input">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1.5">Role *</label>
                            <select id="user-role" required class="form-input">
                                <option value="admin">Admin</option>
                                <option value="teacher">Teacher</option>
                                <option value="staff">Staff</option>
                            </select>
                        </div>
                        <div id="password-group">
                            <label class="block text-sm font-medium mb-1.5">Password *</label>
                            <input id="user-password" type="password" required class="form-input">
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <input id="user-active" type="checkbox" class="w-4 h-4 rounded" checked>
                        <label class="text-sm">Active</label>
                    </div>
                    <div class="pt-4 border-t">
                        <button type="submit" id="save-user" class="btn-gradient text-white px-8 py-2.5 rounded-xl font-medium shadow-lg w-full md:w-auto">
                            Save User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('page-title').textContent = 'User Management';
let users = [];
let editingUserId = null;

// Load users
async function loadUsers() {
    try {
        users = await window.app.usersAPI.getAll();
        renderTable();
    } catch (error) {
        toast('Failed to load users: ' + error.message, 'error');
        document.querySelector('#users-table tbody').innerHTML = '<tr><td colspan="7" class="text-center py-8 text-red-500">Failed to load data. Check backend.</td></tr>';
    }
}

function renderTable() {
    const tbody = document.querySelector('#users-table tbody');
    const searchTerm = document.getElementById('user-search').value.toLowerCase();
    const roleFilter = document.getElementById('role-filter').value;
    
    const filtered = users.filter(user => 
        (user.username.toLowerCase().includes(searchTerm) || 
         user.full_name.toLowerCase().includes(searchTerm)) &&
        (!roleFilter || user.role === roleFilter)
    );
    
    if (filtered.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" class="text-center py-12 text-gray-500">No users found</td></tr>';
        return;
    }
    
    tbody.innerHTML = filtered.map(user => `
        <tr class="hover:bg-gray-50">
            <td class="font-mono font-medium">${user.username}</td>
            <td class="font-medium">${user.full_name}</td>
            <td>${user.email}</td>
            <td>
                <span class="px-2 py-1 text-xs rounded-full ${
                    user.role === 'admin' ? 'bg-red-100 text-red-800' :
                    user.role === 'teacher' ? 'bg-blue-100 text-blue-800' :
                    'bg-green-100 text-green-800'
                }">
                    ${user.role}
                </span>
            </td>
            <td>
                <span class="px-2 py-1 text-xs rounded-full ${user.is_active ? 'status-normal' : 'bg-gray-100 text-gray-800'} border">
                    ${user.is_active ? 'Active' : 'Inactive'}
                </span>
            </td>
            <td>${new Date(user.created_at).toLocaleDateString()}</td>
            <td class="flex gap-2">
                <button onclick="editUser('${user.id}')" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg" title="Edit">
                    <i data-lucide="edit" class="w-4 h-4"></i>
                </button>
                <button onclick="deleteUser('${user.id}')" class="p-2 text-red-600 hover:bg-red-50 rounded-lg" title="Delete">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                </button>
            </td>
        </tr>
    `).join('');
    
    lucide.createIcons();
}

// Modal handlers
document.getElementById('add-user-btn').onclick = () => openUserModal(null);
document.getElementById('user-form').onsubmit = saveUser;

// Search/Filter
document.getElementById('user-search').oninput = renderTable;
document.getElementById('role-filter').onchange = renderTable;

function openUserModal(userId) {
    editingUserId = userId;
    document.getElementById('user-modal-title').textContent = userId ? 'Edit User' : 'Add User';
    document.getElementById('user-form').reset();
    document.getElementById('user-active').checked = true;
    
    // Toggle password field visibility
    const passwordGroup = document.getElementById('password-group');
    if (userId) {
        passwordGroup.classList.add('hidden');
        const user = users.find(u => u.id === userId);
        document.getElementById('user-username').value = user.username;
        document.getElementById('user-email').value = user.email;
        document.getElementById('user-full-name').value = user.full_name;
        document.getElementById('user-role').value = user.role;
        document.getElementById('user-active').checked = user.is_active;
        document.getElementById('save-user').textContent = 'Update User';
    } else {
        passwordGroup.classList.remove('hidden');
        document.getElementById('user-password').required = true;
        document.getElementById('save-user').textContent = 'Create User';
    }
    
    document.getElementById('user-modal').classList.remove('hidden');
}

function closeUserModal() {
    document.getElementById('user-modal').classList.add('hidden');
}

async function saveUser(e) {
    e.preventDefault();
    
    const userData = {
        username: document.getElementById('user-username').value,
        email: document.getElementById('user-email').value,
        fullName: document.getElementById('user-full-name').value,
        role: document.getElementById('user-role').value,
    };
    
    // Add password only for new users
    if (!editingUserId) {
        userData.password = document.getElementById('user-password').value;
    }
    
    try {
        if (editingUserId) {
            await window.app.usersAPI.update(editingUserId, userData);
            toast('User updated successfully', 'success');
        } else {
            await window.app.usersAPI.create(userData);
            toast('User created successfully', 'success');
        }
        loadUsers();
        closeUserModal();
    } catch (error) {
        toast(error.message, 'error');
    }
}

async function deleteUser(id) {
    if (confirm('Delete this user? This action cannot be undone.')) {
        try {
            await window.app.usersAPI.delete(id);
            toast('User deleted', 'success');
            loadUsers();
        } catch (error) {
            toast(error.message, 'error');
        }
    }
}

function editUser(id) {
    openUserModal(id);
}

// Init
loadUsers();
lucide.createIcons();
</script>
