// Carousel functionality
const carousel = document.getElementById('carousel').querySelector('.flex');
const prevBtn = document.getElementById('prevBtn');
const nextBtn = document.getElementById('nextBtn');
let currentIndex = 0;
const intervalTime = 3000; // Time in milliseconds (3 seconds)
let autoScrollInterval;

// Number of images in the carousel
const totalImages = carousel.children.length;

function startAutoScroll() {
    autoScrollInterval = setInterval(() => {
        currentIndex = (currentIndex + 1) % totalImages;
        updateCarousel();
    }, intervalTime);
}

function stopAutoScroll() {
    clearInterval(autoScrollInterval);
}

nextBtn.addEventListener('click', () => {
    stopAutoScroll();
    currentIndex = (currentIndex < totalImages - 1) ? currentIndex + 1 : 0;
    updateCarousel();
    startAutoScroll();
});

prevBtn.addEventListener('click', () => {
    stopAutoScroll();
    currentIndex = (currentIndex > 0) ? currentIndex - 1 : totalImages - 1;
    updateCarousel();
    startAutoScroll();
});

function updateCarousel() {
    carousel.style.transform = `translateX(-${currentIndex * 100}%)`;
}

// Start automatic scrolling when the page loads
startAutoScroll();

// Form validation (if necessary)
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    if (form) {
        const errorMessage = document.createElement('p');
        errorMessage.className = 'text-red-500 mt-2';
        form.appendChild(errorMessage);

        form.addEventListener('submit', function (event) {
            const newPassword = document.getElementById('new-password').value;
            const confirmPassword = document.getElementById('confirm-password').value;

            if (newPassword === confirmPassword) {
                errorMessage.textContent = '';
                window.location.href = 'login.php';
            } else {
                event.preventDefault(); // Prevent form submission
                errorMessage.textContent = 'Passwords do not match. Please try again.';
            }
        });
    }
});

// Menu toggling for mobile view
function handleMenu() {
    const navbar = document.getElementById('navbar');
    const rightPosition = navbar.style.right;

    if (rightPosition === '0px') {
        navbar.style.right = '-100%';
    } else {
        navbar.style.right = '0px';
    }
}

// Search bar toggling
function toggleSearchBar() {
    const searchBar = document.getElementById('searchBar');
    if (searchBar) {
        searchBar.classList.toggle('hidden');
    }
}

// OTP Section Handling (if relevant)
function showOtpSection(event) {
    event.preventDefault();  // Prevent form from submitting immediately

    // Hide email input and send OTP button
    document.getElementById('email-section').style.display = 'none';
    document.getElementById('send-otp-section').style.display = 'none';

    // Show OTP input and verify OTP button
    document.getElementById('otp-section').style.display = 'block';
}

// Another form submission handler (for an alternate form, if present)
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function (event) {
        const newPassword = document.getElementById('new-password');
        const confirmPassword = document.getElementById('confirm-password');

        if (newPassword && confirmPassword && newPassword.value !== confirmPassword.value) {
            event.preventDefault(); // Prevent form submission
            const errorMessage = document.getElementById('error-message');
            errorMessage.textContent = 'Passwords do not match. Please try again.';
        }
    });
});

