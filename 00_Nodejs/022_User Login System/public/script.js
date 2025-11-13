// API base URL
const API_BASE = '/api/auth';

// Show message
function showMessage(message, type = 'error') {
  const messageDiv = document.getElementById('message');
  messageDiv.textContent = message;
  messageDiv.className = `message ${type}`;
  messageDiv.style.display = 'block';
  
  if (type === 'success') {
    setTimeout(() => {
      messageDiv.style.display = 'none';
    }, 3000);
  }
}

// Get token from localStorage
function getToken() {
  return localStorage.getItem('token');
}

// Set token in localStorage
function setToken(token) {
  localStorage.setItem('token', token);
}

// Remove token from localStorage
function removeToken() {
  localStorage.removeItem('token');
}

// Check if user is logged in
function checkAuth() {
  const token = getToken();
  const publicPages = ['/', '/register'];
  const currentPath = window.location.pathname;
  
  if (token && publicPages.includes(currentPath)) {
    window.location.href = '/dashboard';
  } else if (!token && currentPath === '/dashboard') {
    window.location.href = '/';
  }
}

// Handle login form submission
if (document.getElementById('loginForm')) {
  document.getElementById('loginForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    
    try {
      const response = await fetch(`${API_BASE}/login`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ email, password }),
      });
      
      const data = await response.json();
      
      if (data.success) {
        setToken(data.token);
        showMessage('Login successful! Redirecting...', 'success');
        setTimeout(() => {
          window.location.href = '/dashboard';
        }, 1000);
      } else {
        showMessage(data.message);
      }
    } catch (error) {
      showMessage('Error logging in. Please try again.');
    }
  });
}

// Handle register form submission
if (document.getElementById('registerForm')) {
  document.getElementById('registerForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const username = document.getElementById('username').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    
    try {
      const response = await fetch(`${API_BASE}/register`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ username, email, password }),
      });
      
      const data = await response.json();
      
      if (data.success) {
        setToken(data.token);
        showMessage('Registration successful! Redirecting...', 'success');
        setTimeout(() => {
          window.location.href = '/dashboard';
        }, 1000);
      } else {
        showMessage(data.message);
      }
    } catch (error) {
      showMessage('Error registering. Please try again.');
    }
  });
}

// Load dashboard data
if (document.getElementById('dashboard')) {
  async function loadDashboard() {
    const token = getToken();
    
    if (!token) {
      window.location.href = '/';
      return;
    }
    
    try {
      const response = await fetch(`${API_BASE}/me`, {
        headers: {
          'Authorization': `Bearer ${token}`,
        },
      });
      
      const data = await response.json();
      
      if (data.success) {
        document.getElementById('userInfo').innerHTML = `
          <p><strong>Username:</strong> ${data.user.username}</p>
          <p><strong>Email:</strong> ${data.user.email}</p>
        `;
      } else {
        removeToken();
        window.location.href = '/';
      }
    } catch (error) {
      removeToken();
      window.location.href = '/';
    }
  }
  
  // Logout function
  window.logout = function() {
    removeToken();
    window.location.href = '/';
  };
  
  loadDashboard();
}

// Check authentication on page load
document.addEventListener('DOMContentLoaded', checkAuth);