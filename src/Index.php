<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main page - ChARM</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.0.5/css/boxicons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap" rel="stylesheet">
</head>
<body class="font-lexend bg-gradient-to-r from-custom_orange via-bright_orange to-dark_cream">

    <!-- Header -->
    <header class="flex justify-between items-center p-4">
        <a href="#" class="flex gap-2 items-center">
            <img class="max-h-9 w-auto ml-1 md:ml-10"src="asset/logo_Charm.png" alt="logo_Charm">
            <span class="text-lg font-semibold mt-2 text-custom_black">CHARM</span>
        </a>
        <a href="#maps" class="text-sm text-custom_black font-semibold mr-10 max-md:hidden hover:text-custom_orange">View Our Shop Location</a>
        <a href="#maps" class="md:hidden">
            <i class='bx bx-map-alt bx-tada bx-rotate-180 text-3xl text-custom_black' ></i>
        </a>
    </header>

    <!-- Welcome Section -->
    <section class="container h-auto min-h-[80vh] max-w-5xl mx-auto py-16 px-4 flex flex-col md:flex-row items-center justify-between">
        <div class="text-center md:text-left md:w-1/2 space-y-4">
            <h1 class="text-4xl text-custom_black font-bold hover:text-bright_cream">Welcome!</h1>
            <p class="text-lg text-dark_brown">
                Transformasi menjadi karakter anime impianmu kini semakin mudah! 
                Dengan layanan sewa kostum anime kami, Anda bisa menyewa berbagai macam kostum anime dengan kualitas terbaik. 
                Proses pemesanan mudah, pengiriman cepat, dan harga terjangkau.
            </p>
            <div class="space-x-0 sm:space-x-4 space-y-4 sm:space-y-0">
                <button class="px-6 py-2 bg-dark_orange text-custom_white rounded-lg hover:bg-bright_cream hover:text-dark_cream">
                    <a href="login.php">Log In</a>
                </button>
                <a href="Register.php" class="px-6 py-2 text-custom_brown border-2 border-dark_orange rounded-lg hover:bg-bright_cream hover:text-custom_brown hover:border-bright_cream">
                    Sign Up
                </a>
            </div>
        </div>
        
        <div class="relative w-full max-w-xs h-64 overflow-hidden rounded-lg shadow-lg">
            <!-- Images -->
            <div id="carousel" class="flex transition-transform duration-500 ease-in-out">
                <div class="flex-none w-full h-full">
                    <img src="asset/costum-image-1.jpg" alt="Image 1" class="w-full h-full object-cover">
                </div>
                <div class="flex-none w-full h-full">
                    <img src="asset/costum-image-2.jpg" alt="Image 2" class="w-full h-full object-cover">
                </div>
                <div class="flex-none w-full h-full">
                    <img src="asset/costume-image-4.jpg" alt="Image 3" class="w-full h-full object-cover">
                </div>
            </div>
    
            <!-- Previous Button -->
            <button id="prevBtn" class="absolute top-1/2 left-0 transform -translate-y-1/2 p-2 text-custom_black hover:translate-x-[-0.5rem]">
                <i class='bx bx-chevron-left text-3xl'></i>
            </button>
    
            <!-- Next Button -->
            <button id="nextBtn" class="absolute top-1/2 right-0 transform -translate-y-1/2 p-2 text-custom_black hover:translate-x-[0.5rem]">
                <i class='bx bx-chevron-right text-3xl'></i>
            </button>
        </div>
    </section>

    <!-- Map Section -->
    <section class="py-16" id="maps">
        <div class="container mx-auto text-center">
            <h1 class="text-xl font-bold m-10 text-custom_black">Hey, it's Here!</h1>
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3964.5635672188887!2d106.88717367446999!3d-6.45003369354136!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69eb0015c1c8bd%3A0xa7d4bed469af51e5!2sWarung%20bekicot%20di%20magetan!5e0!3m2!1sid!2sid!4v1724594689867!5m2!1sid!2sid"
                class="w-full h-96 border-0"
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </section>

        <script>
        const carousel = document.getElementById('carousel');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        let index = 0;

        // Function to show the current slide
        function showSlide(index) {
            const slides = carousel.children;
            const totalSlides = slides.length;
            carousel.style.transform = `translateX(-${index * 100}%)`;
        }

        // Event listeners for next and previous buttons
        nextBtn.addEventListener('click', () => {
            index = (index + 1) % carousel.children.length; // Loop back to first slide
            showSlide(index);
        });

        prevBtn.addEventListener('click', () => {
            index = (index - 1 + carousel.children.length) % carousel.children.length; // Loop back to last slide
            showSlide(index);
        });

        // Automatic sliding
        setInterval(() => {
            index = (index + 1) % carousel.children.length;
            showSlide(index);
        }, 3000); // 3 seconds interval for auto slide
    </script>


    <!-- External Script -->
    <script src="../src/script.js"></script>
    <script src="script_login-page.js"></script>
    <script src="C:\laragon\www\ChARM\src\script.js"></script>

</body>
</html>
