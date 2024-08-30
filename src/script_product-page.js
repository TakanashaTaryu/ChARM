const carousel = document.getElementById('carousel');
const images = carousel.children;
const totalImages = images.length;
let currentIndex = 0;
const intervalTime = 60000; 

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

function handleMenu() {
    const navbar = document.getElementById('navbar');
    const rightPosition = navbar.style.right;

    if (rightPosition === '0px') {
        navbar.style.right = '-100%';
    } else {
        navbar.style.right = '0px';
    }
}



function filterProducts(category) {
    const categories = document.querySelectorAll('.product-category');
    categories.forEach((cat) => {
        if (cat.id === category) {
            cat.classList.remove('show');
        } else {
            cat.classList.add('show');
        }
    });

    const buttons = document.querySelectorAll('button');
    buttons.forEach((button) => {
        if (button.textContent.trim().toLowerCase() === category) {
            button.classList.remove('bg-gray-200', 'text-black');
            button.classList.add('bg-black', 'text-white');
        } else {
            button.classList.remove('bg-black', 'text-white');
            button.classList.add('bg-gray-200', 'text-black');
        }
    });
}

// Initialize by showing the first category by default
filterProducts('anime');