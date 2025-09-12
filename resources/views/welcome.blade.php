<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>EmergingTech</title>
    <!-- ✅ Add favicon -->
    <link rel="icon" type="image/png" href="{{ asset('Et.webp') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
  <style>
    .icon-hover {
      transition: transform 0.3s ease-in-out;
    }
    .card:hover .icon-hover {
      transform: translateY(-5px) scale(1.1);
    }
  </style>
  
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
</head>
<body class="bg-gray-50 text-gray-800">
 
  <!-- Navbar -->
  <nav class="bg-[#0B2540] text-white px-4 sm:px-6 md:pl-[9rem] md:pr-[4.8rem] py-4 flex justify-between items-center">
    <h1 class="text-2xl font-bold">EmergingTech</h1>
    <div class="flex items-center space-x-6">
      <ul class="hidden md:flex space-x-6">
        <li><a href="#" class="hover:text-orange-500">Home</a></li>
        <li><a href="#about" class="hover:text-orange-500">About</a></li>
        <li><a href="#courses" class="hover:text-orange-500">Courses</a></li>
        <li><a href="#contact" class="hover:text-orange-500">Contact</a></li>
      </ul>
      <!-- Login Button -->
      {{-- <a href="{{ route('login') }}" class="bg-orange-500 hover:bg-orange-600 px-4 py-2 rounded-lg font-semibold text-white shadow-md">
        Login
      </a> --}}
       @if (Route::has('login'))
            <nav class="flex items-center gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}"
                        class="bg-orange-500 hover:bg-orange-600 px-4 py-2 rounded-lg font-semibold text-white shadow-md">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="bg-orange-500 hover:bg-orange-600 px-4 py-2 rounded-lg font-semibold text-white shadow-md"
                        onmouseout="this.style.backgroundColor='transparent'">
                        Login
                    </a>

                    {{-- @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                               class="px-5 py-2 rounded-md border dark:border-[#3E3E3A] text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-black hover:text-white transition">
                               Register
                            </a>
                        @endif --}}
                @endauth
            </nav>
        @endif
    </div>
  </nav>
 
  <!-- Banner -->
  <section class="bg-[#0B2540] text-white py-20 px-6 sm:px-8 md:px-16 flex flex-col md:flex-row items-center">
    <div class="md:w-1/2 space-y-6 px-4 sm:px-6 md:pl-[7.5rem] md:pr-[3.5rem]">
      <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold leading-tight">
        Explore the Future with <span class="text-orange-500">EmergingTech</span>
      </h2>
      <p class="text-base sm:text-lg text-gray-300">
        Discover the latest innovations in Artificial Intelligence,IoT & Robotics, and CyberSecurity. Empower your career with cutting-edge skills.
      </p>
      {{-- <button class="bg-orange-500 hover:bg-orange-600 px-6 py-3 rounded-lg font-semibold text-white shadow-md">
        Get Started
      </button> --}}
    </div>
    <div class="md:w-1/2 mt-8 md:mt-0 px-4 sm:px-6 md:pl-[8rem] md:pr-[4rem]">
      <img
        src="{{ asset('Et.webp') }}"
        alt="EmergingTech"
        class="rounded-lg shadow-lg max-w-full h-auto"
      />
    </div>
  </section>
 
  <!-- About Section -->
  <section id="about" class="py-16 px-4 sm:px-6 md:px-12 bg-[#F5F7FA]">
    <div class="max-w-4xl mx-auto text-center">
      <h2 class="text-3xl font-bold text-[#0B2540] mb-6">About Us</h2>
      <p class="text-lg text-gray-700 leading-relaxed">
        At EmergingTech, we believe in preparing the next generation for tomorrow’s world. Our mission is to provide immersive learning experiences in the most in-demand technologies shaping the future.
      </p>
    </div>
  </section>
 
  <!-- Courses Section -->
  <section id="courses" class="py-16 px-4 sm:px-6 md:px-12 bg-[#E8F0FE]">
    <div class="max-w-6xl mx-auto text-center">
      <h2 class="text-3xl font-bold text-[#0B2540] mb-12">Our Courses</h2>
      <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-8">
       
        <!-- AI Card -->
        <div class="card relative bg-white/90 backdrop-blur-md rounded-2xl shadow-lg border border-gray-200
                    hover:shadow-2xl hover:scale-[1.03] transform transition duration-300 overflow-hidden">
          <img
            src="{{ asset('images/AI.jpg') }}"
            alt="Artificial Intelligence"
            class="w-full h-40 object-cover"
          />
          <div class="p-6">
            <h3 class="text-xl font-bold text-[#0B2540] mb-3">Artificial Intelligence</h3>
            <p class="text-gray-600">
              Learn the fundamentals and advanced concepts of AI, from machine learning to deep learning applications.
            </p>
          </div>
        </div>
 
        <!-- Robotics Card -->
        <div class="card relative bg-white/90 backdrop-blur-md rounded-2xl shadow-lg border border-gray-200
                    hover:shadow-2xl hover:scale-[1.03] transform transition duration-300 overflow-hidden">
          <img
            src="{{ asset('images/iotr.webp') }}"
            alt="Robotics"
            class="w-full h-40 object-cover"
          />
          <div class="p-6">
            <h3 class="text-xl font-bold text-[#0B2540] mb-3">IoT & Robotics</h3>
            <p class="text-gray-600">
              Dive into the exciting world of robotics, automation, and intelligent machines that shape industries.
            </p>
          </div>
        </div>
 
        <!-- Cyber Security Card -->
        <div class="card relative bg-white/90 backdrop-blur-md rounded-2xl shadow-lg border border-gray-200
                    hover:shadow-2xl hover:scale-[1.03] transform transition duration-300 overflow-hidden">
          <img
            src="{{ asset('images/cyber_security.jpg') }}"
            alt="Cyber Security"
            class="w-full h-40 object-cover"
          />
          <div class="p-6">
            <h3 class="text-xl font-bold text-[#0B2540] mb-3">Cyber Security</h3>
            <p class="text-gray-600">
              Protect the digital world by mastering cyber defense strategies and security protocols.
            </p>
          </div>
        </div>
 
      </div>
    </div>
  </section>
 
  <!-- Footer -->
  <footer id="contact" class="bg-[#0B2540] text-white py-8 px-4 sm:px-6 md:px-12">
    <div class="max-w-6xl mx-auto grid sm:grid-cols-2 md:grid-cols-3 gap-8">
      <div>
        <h3 class="text-xl font-bold mb-4">EmergingTech</h3>
        <p class="text-gray-300">
          Shaping the future through innovation and technology education.
        </p>
      </div>
      <div>
        <h3 class="text-xl font-bold mb-4">Quick Links</h3>
        <ul class="space-y-2">
          <li><a href="#" class="hover:text-orange-500">Home</a></li>
          <li><a href="#about" class="hover:text-orange-500">About</a></li>
          <li><a href="#courses" class="hover:text-orange-500">Courses</a></li>
          <li><a href="#contact" class="hover:text-orange-500">Contact</a></li>
        </ul>
      </div>
      <div>
        <h3 class="text-xl font-bold mb-4">Contact</h3>
        <p>Email: help@okcl.org</p>
        <p>Phone: +91 67435200210</p>
      </div>
    </div>r
    <div class="text-center text-gray-400 mt-8 bg-">
      &copy; 2025 EmergingTech. All rights reserved.
    </div>
  </footer>
    <button
    id="backToTop"
    class="fixed bottom-6 right-6 z-50 bg-orange-500 hover:bg-orange-600 text-white rounded-full p-3 shadow-lg transition-opacity opacity-0 pointer-events-none"
    aria-label="Back to Top"
    onclick="window.scrollTo({top: 0, behavior: 'smooth'});"
  >
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
    </svg>
  </button>
 
  <script>
    lucide.createIcons();
  </script>
    <script>
    lucide.createIcons();
    // Show/hide Back to Top button
    window.addEventListener('scroll', function() {
      const btn = document.getElementById('backToTop');
      if (window.scrollY > 200) {
        btn.style.opacity = '1';
        btn.style.pointerEvents = 'auto';
      } else {
        btn.style.opacity = '0';
        btn.style.pointerEvents = 'none';
      }
    });
  </script>
</body>
</html>
 
 