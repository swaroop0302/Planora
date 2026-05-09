// Mobile Menu Toggle
const menuToggle = document.getElementById('mobile-menu');
const navMenu = document.querySelector('.nav-menu');

if (menuToggle && navMenu) {
    menuToggle.addEventListener('click', () => {
        menuToggle.classList.toggle('active');
        navMenu.classList.toggle('active');
        document.body.style.overflow = navMenu.classList.contains('active') ? 'hidden' : '';
    });

    // Close mobile menu when clicking on a nav link
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', () => {
            navMenu.classList.remove('active');
            menuToggle.classList.remove('active');
            document.body.style.overflow = '';
        });
    });
}

// Header scroll effect
const header = document.querySelector('.header');
let lastScroll = 0;

window.addEventListener('scroll', () => {
    const currentScroll = window.pageYOffset;
    
    if (currentScroll > 100) {
        header.classList.add('scrolled');
    } else {
        header.classList.remove('scrolled');
    }
    
    lastScroll = currentScroll;
});

// Smooth scroll for anchor links (only for internal page links)
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        const href = this.getAttribute('href');
        // Only handle internal anchor links, not external pages
        if (href !== '#' && href !== '#login' && href !== '#signup' && !href.includes('.html')) {
            e.preventDefault();
            const target = document.querySelector(href);
            if (target) {
                const headerOffset = 80;
                const elementPosition = target.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
            }
        }
    });
});

// Intersection Observer for fade-in animations
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('animate-in');
            observer.unobserve(entry.target);
        }
    });
}, observerOptions);

// Observe elements for animation
document.addEventListener('DOMContentLoaded', () => {
    // Observe cards
    const cards = document.querySelectorAll('.destination-card, .package-card, .service-card, .feature-card');
    cards.forEach(card => {
        observer.observe(card);
    });

    // Observe sections
    const sections = document.querySelectorAll('.section-title, .welcome-content');
    sections.forEach(section => {
        observer.observe(section);
    });
});

// Page load handler
window.addEventListener('load', function() {
    // Add 'loaded' class to body to start animations
    document.body.classList.add('loaded');
    
    // Hide preloader with a slight delay for smoother transition
    const preloader = document.getElementById('preloader');
    if (preloader) {
        setTimeout(() => {
            preloader.classList.add('fade-out');
            // Remove preloader from DOM after animation completes
            setTimeout(() => {
                preloader.remove();
            }, 500);
        }, 500);
    }
});

// Testimonial Slider
const testimonialSlides = document.querySelectorAll('.testimonial-slide');
const dots = document.querySelectorAll('.dot');
const prevBtn = document.querySelector('.slider-prev');
const nextBtn = document.querySelector('.slider-next');
let currentSlide = 0;

function showSlide(n) {
    if (testimonialSlides.length === 0) return;
    
    testimonialSlides.forEach(slide => slide.classList.remove('active'));
    dots.forEach(dot => {
        dot.classList.remove('active');
        dot.setAttribute('aria-selected', 'false');
    });
    
    currentSlide = (n + testimonialSlides.length) % testimonialSlides.length;
    
    testimonialSlides[currentSlide].classList.add('active');
    if (dots[currentSlide]) {
        dots[currentSlide].classList.add('active');
        dots[currentSlide].setAttribute('aria-selected', 'true');
    }
}

if (prevBtn) {
    prevBtn.addEventListener('click', () => {
        showSlide(currentSlide - 1);
    });
}

if (nextBtn) {
    nextBtn.addEventListener('click', () => {
        showSlide(currentSlide + 1);
    });
}

dots.forEach((dot, index) => {
    dot.addEventListener('click', () => {
        showSlide(index);
    });
});

// Auto-advance testimonials every 5 seconds
if (testimonialSlides.length > 0) {
    setInterval(() => {
        showSlide(currentSlide + 1);
    }, 5000);
}

// Parallax effect for hero section
const hero = document.querySelector('.hero');
if (hero) {
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        const parallaxSpeed = 0.5;
        hero.style.transform = `translateY(${scrolled * parallaxSpeed}px)`;
    });
}

// Counter animation for statistics (if you add them)
function animateCounter(element, target, duration = 2000) {
    let start = 0;
    const increment = target / (duration / 16);
    
    const timer = setInterval(() => {
        start += increment;
        if (start >= target) {
            element.textContent = Math.round(target);
            clearInterval(timer);
        } else {
            element.textContent = Math.round(start);
        }
    }, 16);
}

// Add loading animation
window.addEventListener('load', () => {
    document.body.classList.add('loaded');
});

// Lazy loading for images
if ('IntersectionObserver' in window) {
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                if (img.dataset.src) {
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    observer.unobserve(img);
                }
            }
        });
    });

    document.querySelectorAll('img[data-src]').forEach(img => {
        imageObserver.observe(img);
    });
}

// Add hover effect sound (optional - commented out by default)
/*
const hoverSound = new Audio('path/to/hover-sound.mp3');
document.querySelectorAll('.btn, .card').forEach(element => {
    element.addEventListener('mouseenter', () => {
        hoverSound.currentTime = 0;
        hoverSound.play();
    });
});
*/

// Form validation helper
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(String(email).toLowerCase());
}

// Sign In Modal Functionality
const signinBtn = document.getElementById('signin-btn');
const signinModal = document.getElementById('signin-modal');
const closeModal = document.querySelector('.close-modal');
const signinForm = document.getElementById('signin-form');

// Open modal when sign in button is clicked
if (signinBtn && signinModal) {
    signinBtn.addEventListener('click', (e) => {
        e.preventDefault();
        signinModal.classList.add('active');
        document.body.style.overflow = 'hidden';
    });
}

// Close modal when X is clicked
if (closeModal && signinModal) {
    closeModal.addEventListener('click', () => {
        signinModal.classList.remove('active');
        document.body.style.overflow = '';
    });
}

// Close modal when clicking outside the modal content
if (signinModal) {
    signinModal.addEventListener('click', (e) => {
        if (e.target === signinModal) {
            signinModal.classList.remove('active');
            document.body.style.overflow = '';
        }
    });
}

// Handle form submission
if (signinForm) {
    signinForm.addEventListener('submit', (e) => {
        e.preventDefault();
        
        // Get form values
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        
        // Here you would typically send this data to your server
        console.log('Sign in attempt with:', { email, password });
        
        // For demo purposes, just close the modal and show a success message
        signinModal.classList.remove('active');
        document.body.style.overflow = '';
        alert('Sign in successful! (This is a demo)');
        
        // Clear the form
        signinForm.reset();
    });
}

// Add ripple effect to buttons
document.querySelectorAll('.btn').forEach(button => {
    button.addEventListener('click', function(e) {
        const ripple = document.createElement('span');
        const rect = this.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = e.clientX - rect.left - size / 2;
        const y = e.clientY - rect.top - size / 2;
        
        ripple.style.width = ripple.style.height = size + 'px';
        ripple.style.left = x + 'px';
        ripple.style.top = y + 'px';
        ripple.classList.add('ripple');
        
        this.appendChild(ripple);
        
        setTimeout(() => {
            ripple.remove();
        }, 600);
    });
});

// Add CSS for ripple effect
const style = document.createElement('style');
style.textContent = `
    .btn {
        position: relative;
        overflow: hidden;
    }
    .ripple {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.6);
        transform: scale(0);
        animation: ripple-animation 0.6s ease-out;
        pointer-events: none;
    }
    @keyframes ripple-animation {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
    body.loaded {
        animation: fadeIn 0.5s ease-in;
    }
    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }
`;
document.head.appendChild(style);

console.log('✨ Travel Explorer - Professional animations loaded!');
function logout() {
    if (confirm('Are you sure you want to logout?')) {
        localStorage.removeItem('loggedInUser');
        localStorage.removeItem('userId');
        localStorage.removeItem('userEmail');
        sessionStorage.clear();
        window.location.href = 'login.php';
    }
}