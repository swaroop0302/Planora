// auth.js - Authentication Handler

// Check if user is logged in
function isLoggedIn() {
    const userId = localStorage.getItem('userId');
    const userName = localStorage.getItem('loggedInUser');
    return userId && userName;
}

// Get current user info
function getCurrentUser() {
    if (!isLoggedIn()) return null;
    
    return {
        id: localStorage.getItem('userId'),
        name: localStorage.getItem('loggedInUser'),
        email: localStorage.getItem('userEmail')
    };
}

// Logout function
function logout() {
    if (confirm('Are you sure you want to logout?')) {
        localStorage.removeItem('loggedInUser');
        localStorage.removeItem('userId');
        localStorage.removeItem('userEmail');
        sessionStorage.clear();
        window.location.href = 'index.html';
    }
}

// Require authentication for certain pages
function requireAuth(redirectTo = 'login.php') {
    if (!isLoggedIn()) {
        alert('Please login to access this page');
        window.location.href = redirectTo;
        return false;
    }
    return true;
}

// Update navigation menu based on login status
function updateNavigation() {
    const navMenu = document.querySelector('.nav-menu');
    if (!navMenu) return;

    const user = getCurrentUser();
    
    if (user) {
        // User is logged in - show Dashboard and Logout
        const existingDashboard = navMenu.querySelector('a[href="dashboard.html"]');
        const existingLogout = navMenu.querySelector('.logout-link');
        
        if (!existingDashboard) {
            const dashboardLi = document.createElement('li');
            dashboardLi.innerHTML = `<a href="dashboard.html" class="nav-link">📊 Dashboard</a>`;
            navMenu.appendChild(dashboardLi);
        }
        
        if (!existingLogout) {
            const logoutLi = document.createElement('li');
            logoutLi.innerHTML = `<a href="#" onclick="logout(); return false;" class="nav-link logout-link" style="color: #ef4444;">🚪 Logout</a>`;
            navMenu.appendChild(logoutLi);
        }

        // Update user display in header if exists
        const userDisplay = document.getElementById('userDisplay');
        if (userDisplay) {
            userDisplay.innerHTML = `
                <span style="margin-right: 1rem; font-weight: 600;">👤 ${user.name}</span>
            `;
        }

        // Remove Sign In button if it exists
        const signinBtn = document.getElementById('signin-btn');
        if (signinBtn) {
            signinBtn.style.display = 'none';
        }

        // Update Profile link to show user name
        const profileLink = navMenu.querySelector('a[href="profile.html"]');
        if (profileLink) {
            profileLink.innerHTML = `👤 ${user.name}`;
        }

    } else {
        // User is not logged in - hide Dashboard and Logout
        const dashboardLink = navMenu.querySelector('a[href="dashboard.html"]');
        const logoutLink = navMenu.querySelector('.logout-link');
        
        if (dashboardLink) {
            dashboardLink.parentElement.remove();
        }
        
        if (logoutLink) {
            logoutLink.parentElement.remove();
        }

        // Show Sign In button
        const signinBtn = document.getElementById('signin-btn');
        if (signinBtn) {
            signinBtn.style.display = 'inline-block';
        }
    }
}

// Handle Plan Trip button click
function handlePlanTrip(event) {
    event.preventDefault();
    
    if (!isLoggedIn()) {
        // Not logged in - redirect to login
        alert('Please login to start planning your trip');
        window.location.href = 'login.php';
    } else {
        // Logged in - go to itinerary
        window.location.href = 'itinerary.html';
    }
}

// Initialize authentication on page load
document.addEventListener('DOMContentLoaded', function() {
    updateNavigation();
    
    // Add click handler to Plan Trip buttons
    const planTripButtons = document.querySelectorAll('a[href="login.php"]');
    planTripButtons.forEach(btn => {
        // Only modify if it's the "Plan trip" button
        if (btn.textContent.includes('Plan trip') || btn.classList.contains('btn-plan-trip')) {
            btn.addEventListener('click', handlePlanTrip);
        }
    });

    // Show user info if logged in
    const user = getCurrentUser();
    if (user) {
        console.log('User logged in:', user.name);
    }
});