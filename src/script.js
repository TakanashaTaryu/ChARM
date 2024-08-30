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
    if (currentIndex < totalImages - 1) {
        currentIndex++;
    } else {
        currentIndex = 0; // Loop back to the first image
    }
    updateCarousel();
    startAutoScroll();
});

prevBtn.addEventListener('click', () => {
    stopAutoScroll();
    if (currentIndex > 0) {
        currentIndex--;
    } else {
        currentIndex = totalImages - 1; // Loop to the last image
    }
    updateCarousel();
    startAutoScroll();
});

function updateCarousel() {
    carousel.style.transform = `translateX(-${currentIndex * 100}%)`;
}

// Start automatic scrolling when the page loads
startAutoScroll();

document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const errorMessage = document.createElement('p');
    errorMessage.className = 'text-red-500 mt-2';
    form.appendChild(errorMessage);
  
    form.addEventListener('submit', function(event) {
      const newPassword = document.getElementById('new-password').value;
      const confirmPassword = document.getElementById('confirm-password').value;
  
      if (newPassword === confirmPassword) {
        errorMessage.textContent = '';
        window.location.href = 'login.html';
      } else {
        event.preventDefault(); // Prevent form submission
        errorMessage.textContent = 'Passwords do not match. Please try again.';
      }
    });
  });

  // Initialization for ES Users

function handleMenu() {
    const navbar = document.getElementById('navbar');
    const rightPosition = navbar.style.right;


    if (rightPosition === '0px') {
        navbar.style.right = '-100%';
    } else {
        navbar.style.right = '0px';
    }
}

function toggleSearchBar() {
    const searchBar = document.getElementById('searchBar');
    searchBar.classList.toggle('hidden');
}
