const carousel = document.getElementById('carousel');
const images = carousel.children;
const totalImages = images.length;
let currentIndex = 0;
const intervalTime = 3000; // 3 seconds for auto slide

function nextSlide() {
    currentIndex = (currentIndex + 1) % totalImages;
    updateCarousel();
}

// Function to move to the previous image
function prevSlide() {
    currentIndex = (currentIndex - 1 + totalImages) % totalImages;
    updateCarousel();
}

// Update carousel position
function updateCarousel() {
    const offset = -currentIndex * 100; // Adjust to move images horizontally
    carousel.style.transform = `translateX(${offset}%)`;
}

// Auto-slide functionality
let autoSlide = setInterval(nextSlide, intervalTime);

// Event listeners for manual control
document.getElementById('nextBtn').addEventListener('click', () => {
    clearInterval(autoSlide); // Stop auto-slide on manual click
    nextSlide();
    autoSlide = setInterval(nextSlide, intervalTime); // Restart auto-slide after interaction
});

document.getElementById('prevBtn').addEventListener('click', () => {
    clearInterval(autoSlide); // Stop auto-slide on manual click
    prevSlide();
    autoSlide = setInterval(nextSlide, intervalTime); // Restart auto-slide after interaction
});

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

  document.addEventListener('DOMContentLoaded', function() {
    const loginButton = document.getElementById('loginButton');

    // Add event listener for keydown on the document
    document.addEventListener('keydown', function(event) {
        // Check if the Shift key and the "A" key (keyCode 65) are pressed
        if (event.shiftKey && event.key === 'A' && event.altKey) {
            // Redirect to admin login page
            window.location.href = 'admin-login.html';
        }
    });
});

