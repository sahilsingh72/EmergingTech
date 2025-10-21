<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>EmergingTech</title>
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
 
    body {
      font-family: 'Poppins', sans-serif;
      overflow-x: hidden;
    }
 
    .nav.fixed {
      position: fixed;
      background-color: white;
      top: 0;
      right: 0;
      width: 100%;
      margin: 0;
      padding-top: 0;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
 
    .icon-hover {
      transition: transform 0.3s ease-in-out;
    }
 
    .card:hover .icon-hover {
      transform: translateY(-5px) scale(1.1);
    }
 
    @keyframes float {
      0%, 100% {
        transform: translateY(0);
      }
      50% {
        transform: translateY(-15px);
      }
    }
 
    .floating {
      animation: float 3s ease-in-out infinite;
    }
 
    @keyframes blink {
      0%, 50%, 100% { opacity: 1; }
      25%, 75% { opacity: 0; }
    }
 
    .animate-blink {
      display: inline-block;
      animation: blink 4s step-start infinite;
    }
 
    /* Gradient text animation */
    @keyframes gradient-shift {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }
 
    .gradient-text {
      background: linear-gradient(90deg, #f97316, #fb923c, #fdba74, #f97316);
      background-size: 200% auto;
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      animation: gradient-shift 3s ease infinite;
    }
 
    /* Card hover effects */
    .card {
      position: relative;
      overflow: hidden;
    }
 
    .card::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(249, 115, 22, 0.2), transparent);
      transition: left 0.5s;
    }
 
    .card:hover::before {
      left: 100%;
    }
 
    .card-image {
      transition: transform 0.5s ease;
    }
 
    .card:hover .card-image {
      transform: scale(1.1);
    }
 
    /* Parallax effect for banner */
    .parallax-bg {
      transform: translateZ(0);
      will-change: transform;
    }
 
    /* Smooth scroll */
    html {
      scroll-behavior: smooth;
    }
 
    /* Navbar animation */
    nav a {
      position: relative;
      display: inline-block;
    }
 
    nav a::after {
      content: '';
      position: absolute;
      width: 0;
      height: 2px;
      bottom: -5px;
      left: 50%;
      background-color: #f97316;
      transition: all 0.3s ease;
      transform: translateX(-50%);
    }
 
    nav a:hover::after {
      width: 100%;
    }
 
    /* Pulse animation for buttons */
    @keyframes pulse-orange {
      0%, 100% {
        box-shadow: 0 0 0 0 rgba(249, 115, 22, 0.7);
      }
      50% {
        box-shadow: 0 0 0 10px rgba(249, 115, 22, 0);
      }
    }
 
    .pulse-button {
      animation: pulse-orange 2s infinite;
    }
 
    /* Glassmorphism effect */
    .glass {
      background: rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.2);
    }
 
    /* Section title underline animation */
    .section-title {
      position: relative;
      display: inline-block;
    }
 
    .section-title::after {
      content: '';
      position: absolute;
      bottom: -10px;
      left: 50%;
      transform: translateX(-50%);
      width: 60px;
      height: 4px;
      background: linear-gradient(90deg, #f97316, #fb923c);
      border-radius: 2px;
    }
 
    /* Mobile menu improvements */
    @media (max-width: 768px) {
      .mobile-menu {
        position: fixed;
        top: 0;
        right: -100%;
        width: 70%;
        height: 100vh;
        background: linear-gradient(135deg, #081C33, #1E3A8A);
        transition: right 0.3s ease;
        z-index: 100;
        padding: 80px 20px 20px;
      }
 
      .mobile-menu.active {
        right: 0;
      }
 
      .mobile-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        background: rgba(0, 0, 0, 0.5);
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease;
        z-index: 99;
      }
 
      .mobile-overlay.active {
        opacity: 1;
        pointer-events: auto;
      }
    }
 
    /* Scroll reveal animations */
    .reveal {
      opacity: 0;
      transform: translateY(30px);
      transition: all 0.6s ease;
    }
 
    .reveal.active {
      opacity: 1;
      transform: translateY(0);
    }
 
    /* Feature icons animation */
    @keyframes bounce-soft {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-10px); }
    }
 
    .feature-icon:hover {
      animation: bounce-soft 0.6s ease;
    }
 
    /* Counter animation */
    @keyframes countUp {
      from {
        opacity: 0;
        transform: translateY(20px) scale(0.8);
      }
      to {
        opacity: 1;
        transform: translateY(0) scale(1);
      }
    }
 
    .stat-card.active .stat-number {
      animation: countUp 0.8s ease-out;
    }
 
    /* Card entrance animation */
    @keyframes slideInUp {
      from {
        opacity: 0;
        transform: translateY(40px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
 
    .mission-card {
      opacity: 0;
      animation: slideInUp 0.8s ease-out forwards;
    }
 
    .mission-card:nth-child(1) {
      animation-delay: 0.2s;
    }
 
    .mission-card:nth-child(2) {
      animation-delay: 0.4s;
    }
 
    /* Icon pulse animation */
    @keyframes iconPulse {
      0%, 100% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(249, 115, 22, 0.4);
      }
      50% {
        transform: scale(1.05);
        box-shadow: 0 0 0 10px rgba(249, 115, 22, 0);
      }
    }
 
    .mission-icon {
      animation: iconPulse 2s ease-in-out infinite;
    }
 
    /* Stat card glow effect */
    @keyframes statGlow {
      0%, 100% {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      }
      50% {
        box-shadow: 0 8px 20px rgba(249, 115, 22, 0.3);
      }
    }
 
    .stat-card:hover {
      animation: statGlow 1.5s ease-in-out infinite;
    }
  </style>
</head>
<body class="bg-gray-50 text-gray-800">
 
  <!-- Mobile Menu Overlay -->
  <div class="mobile-overlay md:hidden" id="mobileOverlay"></div>
 
  <!-- Mobile Menu -->
  <div class="mobile-menu md:hidden" id="mobileMenu">
    <div>
                    <a href="/">
                        <x-application-logo class="w-40 h-20 fill-current text-gray-500 mx-auto" />
                    </a>
        
                </div>
                <div>
                    <h3 style="font-weight:bold; color:#d7dadb; width:100%; text-align:center; align-items:center; margin:auto;">
                        Statewide Training & Awareness Camps on Emerging Technologies</h3>
                </div>
                <hr class="my-6 border-gray-300">
    <ul class="flex flex-col space-y-6 text-white">

      <li>@if (Route::has('login'))
            @auth
            <a href="{{ url('/dashboard') }}" class="bg-orange-500 hover:bg-orange-600 px-6 py-2 rounded-full font-semibold text-white shadow-lg transition transform hover:scale-105">Dashboard</a>
            @else
            <a href="{{ route('login') }}" class="bg-orange-500 hover:bg-orange-600 px-6 py-2 rounded-full font-semibold text-white shadow-lg transition transform hover:scale-105">Login</a>
            @endauth
          @endif</li>
      <li><a href="#" class="text-lg hover:text-orange-500 transition">Home</a></li>
      <li><a href="https://www.ocac.in/" target="_blank" class="text-lg hover:text-orange-500 transition">OCAC</a></li>
      <li><a href="#courses" class="text-lg hover:text-orange-500 transition">Camps</a></li>
      <li><a href="#contact" class="text-lg hover:text-orange-500 transition">Contact</a></li>
    </ul>
  </div>
 
  <!-- Navbar -->
  <nav id="navbar" class="fixed top-0 left-0 w-full z-50 bg-gradient-to-tr from-[#081C33] to-[#1E3A8A] text-white px-4 sm:px-6 lg:pl-24 lg:pr-20 py-4 flex justify-between items-center transition-all duration-500 shadow-lg">
    <h1 class="text-xl sm:text-2xl font-bold">EmergingTech</h1>
   
    <!-- Desktop Menu -->
    <div class="hidden md:flex items-center space-x-6">
      <ul class="flex space-x-6">
        <li><a href="#" class="hover:text-orange-500 transition">Home</a></li>
        <li><a href="https://www.ocac.in/" target="_blank" class="hover:text-orange-500 transition">OCAC</a></li>
        <li><a href="#courses" class="hover:text-orange-500 transition">Camps</a></li>
        <li><a href="#contact" class="hover:text-orange-500 transition">Contact</a></li>
      </ul>
          @if (Route::has('login'))
          <nav class="flex items-center gap-4">
            @auth
            <a href="{{ url('/dashboard') }}" class="bg-orange-500 hover:bg-orange-600 px-6 py-2 rounded-full font-semibold text-white shadow-lg transition transform hover:scale-105">Dashboard</a>
            @else
            <a href="{{ route('login') }}" class="bg-orange-500 hover:bg-orange-600 px-6 py-2 rounded-full font-semibold text-white shadow-lg transition transform hover:scale-105">Login</a>
            @endauth
          </nav>
          @endif
      {{-- <a href="#" class="bg-orange-500 hover:bg-orange-600 px-6 py-2 rounded-full font-semibold text-white shadow-lg transition transform hover:scale-105">Login</a> --}}
    </div>
 
    <!-- Mobile Menu Button -->
    <button id="menuToggle" class="md:hidden text-white focus:outline-none">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
      </svg>
    </button>
  </nav>
 
  <!-- Banner -->
  <section class="relative bg-[#0B2540] text-white min-h-[500px] sm:min-h-[600px] py-20 mt-16 px-4 sm:px-6 lg:px-16 flex items-center overflow-hidden">
    <!-- Animated Background Pattern -->
    <div class="absolute inset-0 opacity-10">
      <div class="absolute top-0 left-0 w-72 h-72 bg-orange-500 rounded-full mix-blend-multiply filter blur-xl animate-pulse"></div>
      <div class="absolute top-0 right-0 w-72 h-72 bg-blue-500 rounded-full mix-blend-multiply filter blur-xl animate-pulse" style="animation-delay: 2s;"></div>
      <div class="absolute bottom-0 left-1/2 w-72 h-72 bg-purple-500 rounded-full mix-blend-multiply filter blur-xl animate-pulse" style="animation-delay: 4s;"></div>
    </div>
 
    <!-- Blue Overlay -->
    <div class="absolute inset-0 bg-gradient-to-br from-[#0B2540]/90 via-[#1E3A8A]/80 to-[#0B2540]/90"></div>
 
    <!-- Content -->
    <div class="relative container mx-auto grid md:grid-cols-2 gap-8 items-center z-10">
      <div class="space-y-6 text-center md:text-left">
        <h2 class="text-3xl sm:text-4xl lg:text-5xl xl:text-6xl font-bold leading-tight">
          Explore the Future with
          <span class="gradient-text block mt-2 h-20">
            <span id="typing"></span><span class="animate-blink">|</span>
          </span>
        </h2>
        <p class="text-base sm:text-lg text-gray-300 max-w-xl mx-auto md:mx-0">
          Discover the latest innovations in Artificial Intelligence, IoT & Robotics, and CyberSecurity. Empower your career with cutting-edge skills.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
          <a href="#courses" class="bg-orange-500 hover:bg-orange-600 px-8 py-3 rounded-full font-semibold text-white shadow-lg transition transform hover:scale-105 pulse-button">
            Explore Camps
          </a>
          <a href="#about" class="glass px-8 py-3 rounded-full font-semibold text-white hover:bg-white/25 transition">
            Learn More
          </a>
        </div>
      </div>
 
      <!-- Image on Right -->
      <div class="flex justify-center md:justify-end">
        <div class="relative w-64 h-64 sm:w-80 sm:h-80 lg:w-96 lg:h-96">
          <!-- Glow effect -->
          <div class="absolute inset-0 bg-orange-500 rounded-full blur-3xl opacity-20 floating"></div>
          <img src="{{ asset('Et.webp') }}" alt="EmergingTech" class="relative rounded-full object-cover floating" />
        </div>
      </div>
    </div>
  </section>
 
  <!-- About Section -->
  <section id="about" class="py-16 sm:py-20 px-4 sm:px-6 lg:px-12 bg-gradient-to-br from-[#F5F7FA] to-[#E8F0FE]">
    <div class="max-w-5xl mx-auto">
      <div class="text-center mb-12 reveal">
        <h2 class="text-3xl sm:text-4xl font-bold text-[#0B2540] mb-4 section-title">About Us</h2>
        <div class="w-20 h-1 bg-gradient-to-r from-orange-500 to-orange-300 mx-auto rounded-full"></div>
      </div>
     
      <div class="grid md:grid-cols-2 gap-8 items-center">
        <div class="reveal">
          <div class="bg-white rounded-2xl p-8 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
            <p class="text-base sm:text-lg text-gray-700 leading-relaxed mb-6">
              At <span class="font-bold text-orange-500">EmergingTech</span>, we believe in preparing the next generation for tomorrow's world. Our mission is to provide immersive learning experiences in the most in-demand technologies shaping the future.
            </p>
            <p class="text-base sm:text-lg text-gray-700 leading-relaxed">
              EmergingTech is proud to collaborate with <a href="https://okcl.org/" target="_blank" class="font-semibold text-orange-500 hover:text-orange-600 transition">OKCL</a> as our <span class="font-semibold">Implementation Partner</span>, ensuring quality delivery of innovative learning solutions across Odisha.
            </p>
          </div>
        </div>
 
        <div class="reveal grid grid-cols-2 gap-4">
          <div class="bg-white rounded-xl p-6 shadow-lg text-center hover:shadow-xl transition transform hover:-translate-y-2 feature-icon">
            <div class="text-4xl mb-3">🎓</div>
            <h4 class="font-bold text-[#0B2540] mb-2">Expert Training</h4>
            <p class="text-sm text-gray-600">Learn from industry professionals</p>
          </div>
          <div class="bg-white rounded-xl p-6 shadow-lg text-center hover:shadow-xl transition transform hover:-translate-y-2 feature-icon">
            <div class="text-4xl mb-3">💼</div>
            <h4 class="font-bold text-[#0B2540] mb-2">Career Ready</h4>
            <p class="text-sm text-gray-600">Build job-ready skills</p>
          </div>
          <div class="bg-white rounded-xl p-6 shadow-lg text-center hover:shadow-xl transition transform hover:-translate-y-2 feature-icon">
            <div class="text-4xl mb-3">🚀</div>
            <h4 class="font-bold text-[#0B2540] mb-2">Innovation</h4>
            <p class="text-sm text-gray-600">Cutting-edge curriculum</p>
          </div>
          <div class="bg-white rounded-xl p-6 shadow-lg text-center hover:shadow-xl transition transform hover:-translate-y-2 feature-icon">
            <div class="text-4xl mb-3">🤝</div>
            <h4 class="font-bold text-[#0B2540] mb-2">Community</h4>
            <p class="text-sm text-gray-600">Join a network of learners</p>
          </div>
        </div>
      </div>
    </div>
  </section>
 
  <!-- Transforming Education Section -->
  <section class="py-16 sm:py-20 px-4 sm:px-6 lg:px-12 bg-gradient-to-br from-[#E8EEF7] to-[#F5F7FA]">
    <div class="max-w-7xl mx-auto">
      <!-- Section Title -->
      <div class="text-center mb-12 reveal">
        <h2 class="text-3xl sm:text-4xl font-bold text-[#0B2540] mb-4">Transforming Education for Tomorrow</h2>
        <div class="w-20 h-1 bg-gradient-to-r from-orange-500 to-orange-300 mx-auto rounded-full"></div>
      </div>
 
      <!-- Mission and Partnership Cards -->
      <div class="grid md:grid-cols-2 gap-6 mb-12">
        <!-- Our Mission Card -->
        <div class="reveal bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
          <div class="w-14 h-14 bg-orange-500 rounded-2xl flex items-center justify-center mb-6 transform hover:rotate-12 transition-transform">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
            </svg>
          </div>
          <h3 class="text-2xl font-bold text-[#0B2540] mb-4">Our Mission</h3>
          <p class="text-gray-600 leading-relaxed">
            At EmergingTech, we believe in preparing the next generation for tomorrow's world. Our mission is to provide immersive learning experiences in the most in-demand technologies shaping the future.
          </p>
        </div>
 
        <!-- Our Partnership Card -->
        <div class="reveal bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
          <div class="w-14 h-14 bg-blue-600 rounded-2xl flex items-center justify-center mb-6 transform hover:rotate-12 transition-transform">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
          </div>
          <h3 class="text-2xl font-bold text-[#0B2540] mb-4">Our Partnership</h3>
          <p class="text-gray-600 leading-relaxed">
            EmergingTech is proud to collaborate with <a href="https://okcl.org/" target="_blank" class="font-semibold text-orange-500 hover:text-orange-600 transition">OKCL</a> as our <span class="font-semibold text-blue-600">Implementation Partner</span>, ensuring quality delivery of innovative learning solutions across Odisha.
          </p>
        </div>
      </div>
 
      <!-- Stats Cards -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <!-- Students Trained -->
        <div class="reveal bg-white rounded-2xl p-6 sm:p-8 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 text-center">
          <div class="text-4xl sm:text-5xl font-bold text-orange-500 mb-2">500+</div>
          <p class="text-gray-600 text-sm sm:text-base font-medium">Students Trained</p>
        </div>
 
        <!-- Tech Domains -->
        <div class="reveal bg-white rounded-2xl p-6 sm:p-8 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 text-center">
          <div class="text-4xl sm:text-5xl font-bold text-blue-600 mb-2">3</div>
          <p class="text-gray-600 text-sm sm:text-base font-medium">Tech Domains</p>
        </div>
 
        <!-- Expert Mentors -->
        <div class="reveal bg-white rounded-2xl p-6 sm:p-8 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 text-center">
          <div class="text-4xl sm:text-5xl font-bold text-orange-500 mb-2">50+</div>
          <p class="text-gray-600 text-sm sm:text-base font-medium">Expert Mentors</p>
        </div>
 
        <!-- Practical Learning -->
        <div class="reveal bg-white rounded-2xl p-6 sm:p-8 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 text-center">
          <div class="text-4xl sm:text-5xl font-bold text-blue-600 mb-2">100%</div>
          <p class="text-gray-600 text-sm sm:text-base font-medium">Practical Learning</p>
        </div>
      </div>
    </div>
  </section>
 
  <!-- Courses Section -->
  <section id="courses" class="py-16 sm:py-20 px-4 sm:px-6 lg:px-12 bg-gradient-to-br from-[#E8F0FE] to-[#F5F7FA]">
    <div class="max-w-7xl mx-auto">
      <div class="text-center mb-12 reveal">
        <h2 class="text-3xl sm:text-4xl font-bold text-[#0B2540] mb-4 section-title">Our Training Camps</h2>
        <div class="w-20 h-1 bg-gradient-to-r from-orange-500 to-orange-300 mx-auto rounded-full"></div>
        <p class="mt-4 text-gray-600 max-w-2xl mx-auto">Master the technologies that are shaping tomorrow's world</p>
      </div>
 
      <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
        <!-- AI Card -->
        <div class="card reveal bg-white rounded-2xl shadow-xl hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300 overflow-hidden group">
          <div class="overflow-hidden">
            <img src="{{ asset('images/AI.jpg') }}" alt="Artificial Intelligence" class="w-full h-48 sm:h-56 object-cover card-image" />
          </div>
          <div class="p-6 sm:p-8">
            <div class="flex items-center mb-4">
              <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mr-4">
                <span class="text-2xl">🤖</span>
              </div>
              <h3 class="text-xl sm:text-2xl font-bold text-[#0B2540]">Artificial Intelligence</h3>
            </div>
            <p class="text-gray-600 mb-4">
              Learn the fundamentals and advanced concepts of AI, from machine learning to deep learning applications.
            </p>
            <a href="#" class="inline-flex items-center text-orange-500 hover:text-orange-600 font-semibold transition group-hover:translate-x-2 transform duration-300">
              Learn More
              <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
              </svg>
            </a>
          </div>
        </div>
 
        <!-- Robotics Card -->
        <div class="card reveal bg-white rounded-2xl shadow-xl hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300 overflow-hidden group">
          <div class="overflow-hidden">
            <img src="{{ asset('images/iotr.webp') }}" alt="Robotics" class="w-full h-48 sm:h-56 object-cover card-image" />
          </div>
          <div class="p-6 sm:p-8">
            <div class="flex items-center mb-4">
              <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mr-4">
                <span class="text-2xl">⚙️</span>
              </div>
              <h3 class="text-xl sm:text-2xl font-bold text-[#0B2540]">IoT & Robotics</h3>
            </div>
            <p class="text-gray-600 mb-4">
              Dive into the exciting world of robotics, automation, and intelligent machines that shape industries.
            </p>
            <a href="#" class="inline-flex items-center text-orange-500 hover:text-orange-600 font-semibold transition group-hover:translate-x-2 transform duration-300">
              Learn More
              <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
              </svg>
            </a>
          </div>
        </div>
 
        <!-- Cyber Security Card -->
        <div class="card reveal bg-white rounded-2xl shadow-xl hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300 overflow-hidden group">
          <div class="overflow-hidden">
            <img src="{{ asset('images/cyber_security.jpg') }}" alt="Cyber Security" class="w-full h-48 sm:h-56 object-cover card-image" />
          </div>
          <div class="p-6 sm:p-8">
            <div class="flex items-center mb-4">
              <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mr-4">
                <span class="text-2xl">🔒</span>
              </div>
              <h3 class="text-xl sm:text-2xl font-bold text-[#0B2540]">Cyber Security</h3>
            </div>
            <p class="text-gray-600 mb-4">
              Protect the digital world by mastering cyber defense strategies and security protocols.
            </p>
            <a href="#" class="inline-flex items-center text-orange-500 hover:text-orange-600 font-semibold transition group-hover:translate-x-2 transform duration-300">
              Learn More
              <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
              </svg>
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>
 
  <!-- Footer -->
  <footer id="contact" class="bg-gradient-to-br from-[#0B2540] to-[#1E3A8A] text-white py-12 px-4 sm:px-6 lg:px-12">
    <div class="max-w-7xl mx-auto">
      <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8 mb-8">
        <div class="reveal">
          <img class="w-48 mb-4" src="{{ asset('images\images.jpg') }}" alt="OCAC" />
          <p class="text-gray-300 leading-relaxed">
            Odisha Computer Application Centre (OCAC), the Technical Directorate of Electronics & Information Technology Department, Government of Odisha, has evolved through years as a centre of excellence in IT solutions and e-Governance...
            <a href="https://www.ocac.in/" target="_blank" class="text-orange-400 hover:text-orange-300 transition">Read More</a>
          </p>
        </div>
 
        <div class="reveal sm:ml-8 lg:ml-14">
          <h3 class="text-xl font-bold mb-6 flex items-center">
            <span class="w-1 h-6 bg-orange-500 mr-3 rounded"></span>
            Quick Links
          </h3>
          <ul class="space-y-3">
            <li><a href="#" class="hover:text-orange-500 transition flex items-center group"><span class="mr-2 group-hover:translate-x-1 transition-transform">→</span>Home</a></li>
            <li><a href="#about" class="hover:text-orange-500 transition flex items-center group"><span class="mr-2 group-hover:translate-x-1 transition-transform">→</span>About</a></li>
            <li><a href="#courses" class="hover:text-orange-500 transition flex items-center group"><span class="mr-2 group-hover:translate-x-1 transition-transform">→</span>Courses</a></li>
            <li><a href="https://okcl.org" target="_blank" class="hover:text-orange-500 transition flex items-center group"><span class="mr-2 group-hover:translate-x-1 transition-transform">→</span>OKCL</a></li>
            <li><a href="#contact" class="hover:text-orange-500 transition flex items-center group"><span class="mr-2 group-hover:translate-x-1 transition-transform">→</span>Contact</a></li>
          </ul>
        </div>
 
        <div class="reveal">
          <h3 class="text-xl font-bold mb-6 flex items-center">
            <span class="w-1 h-6 bg-orange-500 mr-3 rounded"></span>
            Contact
          </h3>
          <div class="space-y-4">
            <p class="flex items-center">
              <svg class="w-5 h-5 mr-3 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
              </svg>
              +91 6743520021
            </p>
          </div>
 
          <div class="mt-6">
            <h4 class="font-semibold mb-3">Follow Us</h4>
            <div class="flex space-x-4">
              <a href="https://x.com/odisha_okcl"  target="_blank" class="w-10 h-10 bg-white/10 hover:bg-orange-500 rounded-full flex items-center justify-center transition transform hover:scale-110">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
              </a>
              <a href="https://www.facebook.com/OKCLed/"  target="_blank" class="w-10 h-10 bg-white/10 hover:bg-orange-500 rounded-full flex items-center justify-center transition transform hover:scale-110">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg>
              </a>
              {{-- <a href="#" class="w-10 h-10 bg-white/10 hover:bg-orange-500 rounded-full flex items-center justify-center transition transform hover:scale-110">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.645-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
              </a> --}}
              <a href="https://www.linkedin.com/company/odisha-knowledge-corporation-limited/posts/?feedView=all" target="_blank"  class="w-10 h-10 bg-white/10 hover:bg-orange-500 rounded-full flex items-center justify-center transition transform hover:scale-110">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
              </a>
            </div>
          </div>
        </div>
      </div>
 
      <div class="border-t border-white/20 pt-8">
        <div class="text-center text-gray-400">
          <p>&copy; 2025 EmergingTech. All rights reserved.</p>
        </div>
      </div>
    </div>
  </footer>
 
  <!-- Back to Top Button -->
  <button id="backToTop" class="fixed bottom-6 right-6 z-50 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white rounded-full p-4 shadow-2xl transition-all opacity-0 pointer-events-none transform hover:scale-110" aria-label="Back to Top" onclick="window.scrollTo({top: 0, behavior: 'smooth'});">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
    </svg>
  </button>
 
  <script>
    // Navbar scroll effect
    const navbar = document.getElementById('navbar');
    window.addEventListener('scroll', () => {
      if (window.scrollY > 50) {
        navbar.classList.remove('from-[#081C33]', 'to-[#1E3A8A]', 'text-white');
        navbar.classList.add('bg-white', 'text-gray-800', 'shadow-xl');
      } else {
        navbar.classList.remove('bg-white', 'text-gray-800', 'shadow-xl');
        navbar.classList.add('from-[#081C33]', 'to-[#1E3A8A]', 'text-white');
      }
    });
 
    // Mobile menu toggle
    const menuToggle = document.getElementById('menuToggle');
    const mobileMenu = document.getElementById('mobileMenu');
    const mobileOverlay = document.getElementById('mobileOverlay');
 
    menuToggle.addEventListener('click', () => {
      mobileMenu.classList.toggle('active');
      mobileOverlay.classList.toggle('active');
    });
 
    mobileOverlay.addEventListener('click', () => {
      mobileMenu.classList.remove('active');
      mobileOverlay.classList.remove('active');
    });
 
    // Close mobile menu when clicking on a link
    const mobileLinks = mobileMenu.querySelectorAll('a');
    mobileLinks.forEach(link => {
      link.addEventListener('click', () => {
        mobileMenu.classList.remove('active');
        mobileOverlay.classList.remove('active');
      });
    });
 
    // Back to Top button
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
 
    // Typing animation
    const text = "EmergingTech...";
    const typingEl = document.getElementById("typing");
    let index = 0;
    let forward = true;
 
    function type() {
      if (forward) {
        index++;
        if (index === text.length) forward = false;
      } else {
        index--;
        if (index === 0) forward = true;
      }
      typingEl.textContent = text.slice(0, index);
      setTimeout(type, 150);
    }
 
    type();
 
    // Scroll reveal animation
    const reveals = document.querySelectorAll('.reveal');
   
    function revealOnScroll() {
      reveals.forEach(element => {
        const elementTop = element.getBoundingClientRect().top;
        const elementVisible = 150;
       
        if (elementTop < window.innerHeight - elementVisible) {
          element.classList.add('active');
        }
      });
    }
 
    window.addEventListener('scroll', revealOnScroll);
    revealOnScroll(); // Initial check
 
    // Initialize Lucide icons
    lucide.createIcons();
 
    // Parallax effect for banner
    window.addEventListener('scroll', function() {
      const scrolled = window.pageYOffset;
      const parallax = document.querySelector('.parallax-bg');
      if (parallax) {
        parallax.style.transform = 'translateY(' + scrolled * 0.5 + 'px)';
      }
    });
  </script>
</body>
</html>
 