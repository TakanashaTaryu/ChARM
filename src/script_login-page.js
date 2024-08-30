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


document.getElementById("loginForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Prevent form submission

    var username = document.getElementById("username").value;
    var password = document.getElementById("password").value;

    // Check if the username and password are "admin"
    if (username === "admin" && password === "admin") {
        window.location.href = "adminpage.html"; // Redirect to admin page
    } else {
        window.location.href = "main-page.html"; // Redirect to customer page
    }
});