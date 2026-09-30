/* ========================================
   Wedding Invitation - JavaScript
   ======================================== */

// Wedding Date
const weddingDate = new Date('2026-09-18T10:00:00').getTime();

// DOM Elements
const openingOverlay = document.getElementById('opening-overlay');
const mainContent = document.getElementById('main-content');
const openBtn = document.getElementById('open-invitation');
const musicToggle = document.getElementById('music-toggle');
const printBtn = document.getElementById('print-btn');
const rsvpForm = document.getElementById('rsvp-form');
const rsvpSuccess = document.getElementById('rsvp-success');

// ========================================
// Opening Overlay
// ========================================
openBtn.addEventListener('click', function() {
    openingOverlay.style.opacity = '0';
    openingOverlay.style.transition = 'opacity 0.8s ease';
    
    setTimeout(() => {
        openingOverlay.style.display = 'none';
        mainContent.classList.remove('hidden');
        mainContent.style.animation = 'fadeIn 1s ease';
        
        // Trigger scroll animations
        initScrollAnimations();
        
        // Start countdown
        updateCountdown();
        setInterval(updateCountdown, 1000);
    }, 800);
});

// ========================================
// Countdown Timer
// ========================================
function updateCountdown() {
    const now = new Date().getTime();
    const distance = weddingDate - now;

    if (distance > 0) {
        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        document.getElementById('days').textContent = days;
        document.getElementById('hours').textContent = hours;
        document.getElementById('minutes').textContent = minutes;
        document.getElementById('seconds').textContent = seconds;
    } else {
        document.getElementById('days').textContent = '0';
        document.getElementById('hours').textContent = '0';
        document.getElementById('minutes').textContent = '0';
        document.getElementById('seconds').textContent = '0';
    }
}

// ========================================
// Scroll Animations
// ========================================
function initScrollAnimations() {
    const sections = document.querySelectorAll('section');
    
    sections.forEach(section => {
        section.classList.add('fade-in');
    });

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });

    sections.forEach(section => {
        observer.observe(section);
    });
}

// ========================================
// RSVP Form
// ========================================
rsvpForm.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const name = document.getElementById('guest-name').value;
    const attendance = document.getElementById('attendance').value;
    const count = document.getElementById('guest-count').value;
    const message = document.getElementById('message').value;

    // Here you would typically send this data to a server
    // For now, we'll just show the success message
    
    console.log('RSVP Data:', { name, attendance, count, message });
    
    // Hide form and show success
    rsvpForm.style.display = 'none';
    rsvpSuccess.classList.remove('hidden');
    rsvpSuccess.style.animation = 'fadeIn 0.5s ease';
});

// ========================================
// Music Toggle (Placeholder)
// ========================================
let isPlaying = false;

musicToggle.addEventListener('click', function() {
    isPlaying = !isPlaying;
    
    if (isPlaying) {
        musicToggle.classList.add('playing');
        // Here you would play the music
        // audio.play();
    } else {
        musicToggle.classList.remove('playing');
        // Here you would pause the music
        // audio.pause();
    }
});

// ========================================
// Print Button
// ========================================
printBtn.addEventListener('click', function() {
    window.print();
});

// ========================================
// Smooth Scroll for Navigation
// ========================================
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// ========================================
// Parallax Effect for Hero Section
// ========================================
window.addEventListener('scroll', function() {
    const scrolled = window.pageYOffset;
    const hero = document.querySelector('.hero');
    
    if (hero && scrolled < hero.offsetHeight) {
        hero.style.backgroundPositionY = scrolled * 0.5 + 'px';
    }
});

// ========================================
// Gallery Lightbox (Simple)
// ========================================
document.querySelectorAll('.gallery-item').forEach(item => {
    item.addEventListener('click', function() {
        // Simple click feedback
        this.style.transform = 'scale(0.98)';
        setTimeout(() => {
            this.style.transform = '';
        }, 150);
    });
});

// ========================================
// Initialize
// ========================================
document.addEventListener('DOMContentLoaded', function() {
    // Add loading state
    document.body.style.opacity = '0';
    document.body.style.transition = 'opacity 0.5s ease';
    
    setTimeout(() => {
        document.body.style.opacity = '1';
    }, 100);
});

// ========================================
// Guest Name URL Parameter (Optional)
// ========================================
function getGuestNameFromURL() {
    const urlParams = new URLSearchParams(window.location.search);
    const guestName = urlParams.get('to');
    
    if (guestName) {
        const decodedName = decodeURIComponent(guestName);
        // You can display this in the opening overlay
        console.log('Guest:', decodedName);
    }
}

getGuestNameFromURL();
