<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>EmergingTech</title>
  <!-- ✅ Add favicon -->
  <link rel="icon" type="image/png" href="{{ asset('Et.webp') }}">
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="images\tailwind.min.css"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
  <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600&display=swap" rel="stylesheet">

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
 
    body {
      font-family: 'Poppins', sans-serif;
      overflow-x: hidden;
      position: relative;
    }
     @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700;900&display=swap');
  
  /* OR use another tech font option */
  @import url('https://fonts.googleapis.com/css2?family=Rajdhani:wght@300;400;500;600;700&display=swap');
  
  /* Apply to the typing text */
  #typing {
    font-family: 'Orbitron', sans-serif; /* Modern, futuristic tech font */
    letter-spacing: 2px;
    font-weight: bold;
  }
  
  /* Alternative option - uncomment to use */
  
#typing {
  font-family: 'Orbitron', sans-serif;
  letter-spacing: 2px;
  font-weight: 500;
}
 

    /* Body Particles Background */
    #body-particles {
      position: fixed;
      width: 100%;
      height: 100%;
      top: 0;
      left: 0;
      z-index: 0;
      pointer-events: none;
    }

    /* All sections should be above particles */
    section, nav, footer {
      position: relative;
      z-index: 1;
    }

    /* Particles container for banner */
    #particles-js {
      position: absolute;
      width: 100%;
      height: 100%;
      top: 0;
      left: 0;
      z-index: 1;
    }

    /* Tech image animation */
    .tech-image {
      transition: opacity 0.3s ease, transform 0.5s ease;
    }

    .tech-image:hover {
      transform: scale(1.05);
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
      0%, 50%, 100% {
        opacity: 1;
      }
      25%, 75% {
        opacity: 0;
      }
    }
 
    .animate-blink {
      display: inline-block;
      animation: blink 4s step-start infinite;
    }
 
    @keyframes gradient-shift {
      0% {
        background-position: 0% 50%;
      }
      50% {
        background-position: 100% 50%;
      }
      100% {
        background-position: 0% 50%;
      }
    }

    .banner-image-container {
      position: relative;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .banner-image {
      position: relative;
      z-index: 10;
      max-width: 100%;
      height: auto;
      filter: drop-shadow(0 20px 40px rgba(249, 115, 22, 0.3));
    }
 
    .gradient-text {
      background: linear-gradient(90deg, #f97316, #fb923c, #fdba74, #f97316);
      background-size: 200% auto;
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      animation: gradient-shift 3s ease infinite;
    }
 
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
 
    html {
      scroll-behavior: smooth;
    }
 
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
 
    .glass {
      background: rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.2);
    }
 
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

    /* Tech Icon Animations */
    @keyframes techGlow {
      0%, 100% {
        box-shadow: 0 0 20px rgba(249, 115, 22, 0.3);
      }
      50% {
        box-shadow: 0 0 40px rgba(249, 115, 22, 0.6);
      }
    }

    .tech-icon {
      animation: techGlow 3s ease-in-out infinite;
    }

    @keyframes slideInLeft {
      from {
        opacity: 0;
        transform: translateX(-50px);
      }
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }

    @keyframes slideInRight {
      from {
        opacity: 0;
        transform: translateX(50px);
      }
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }

    .slide-in-left {
      animation: slideInLeft 0.8s ease-out;
    }

    .slide-in-right {
      animation: slideInRight 0.8s ease-out;
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

      .banner-image-container {
        margin-top: 2rem;
      }
    }
 
    .reveal {
      opacity: 0;
      transform: translateY(30px);
      transition: all 0.6s ease;
    }
 
    .reveal.active {
      opacity: 1;
      transform: translateY(0);
    }

    @keyframes bounce-soft {
      0%, 100% {
        transform: translateY(0);
      }
      50% {
        transform: translateY(-10px);
      }
    }
 
    .feature-icon:hover {
      animation: bounce-soft 0.6s ease;
    }
      /* Orbiting Icons Styles */
   /* Orbiting Icons Styles */
  .orbiting-icons-container {
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    pointer-events: none;
  }

  .orbit-icon {
    position: absolute;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
  }

  .tech-badge {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3), 0 0 20px rgba(249, 115, 22, 0.4);
    backdrop-filter: blur(10px);
    border: 3px solid rgba(255, 255, 255, 0.3);
    transition: transform 0.3s ease;
    overflow: hidden;
  }

  .tech-badge:hover {
    transform: scale(1.15);
  }

  .tech-badge img {
    filter: brightness(0.9);
    transition: filter 0.3s ease;
  }

  .tech-badge:hover img {
    filter: brightness(1.1);
  }

  .tech-label {
    font-size: 14px;
    font-weight: 600;
    color: white;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
    background: rgba(0, 0, 0, 0.3);
    padding: 4px 12px;
    border-radius: 12px;
    backdrop-filter: blur(5px);
  }

  /* Orbital Animation Keyframes */
  @keyframes orbit1 {
    0% {
      transform: rotate(0deg) translateX(180px) rotate(0deg);
    }
    100% {
      transform: rotate(360deg) translateX(180px) rotate(-360deg);
    }
  }

  @keyframes orbit2 {
    0% {
      transform: rotate(120deg) translateX(180px) rotate(-120deg);
    }
    100% {
      transform: rotate(480deg) translateX(180px) rotate(-480deg);
    }
  }

  @keyframes orbit3 {
    0% {
      transform: rotate(240deg) translateX(180px) rotate(-240deg);
    }
    100% {
      transform: rotate(600deg) translateX(180px) rotate(-600deg);
    }
  }

  .orbit-icon-1 {
    top: 50%;
    left: 50%;
    animation: orbit1 15s linear infinite;
  }

  .orbit-icon-2 {
    top: 50%;
    left: 50%;
    animation: orbit2 15s linear infinite;
  }

  .orbit-icon-3 {
    top: 50%;
    left: 50%;
    animation: orbit3 15s linear infinite;
  }

  /* Responsive adjustments */
  @media (max-width: 768px) {
    @keyframes orbit1 {
      0% {
        transform: rotate(0deg) translateX(120px) rotate(0deg);
      }
      100% {
        transform: rotate(360deg) translateX(120px) rotate(-360deg);
      }
    }

    @keyframes orbit2 {
      0% {
        transform: rotate(120deg) translateX(120px) rotate(-120deg);
      }
      100% {
        transform: rotate(480deg) translateX(120px) rotate(-480deg);
      }
    }

    @keyframes orbit3 {
      0% {
        transform: rotate(240deg) translateX(120px) rotate(-240deg);
      }
      100% {
        transform: rotate(600deg) translateX(120px) rotate(-600deg);
      }
    }

    .tech-badge {
      width: 55px;
      height: 55px;
    }

    .tech-label {
      font-size: 12px;
      padding: 3px 8px;
    }
  }

  @media (max-width: 480px) {
    @keyframes orbit1 {
      0% {
        transform: rotate(0deg) translateX(90px) rotate(0deg);
      }
      100% {
        transform: rotate(360deg) translateX(90px) rotate(-360deg);
      }
    }

    @keyframes orbit2 {
      0% {
        transform: rotate(120deg) translateX(90px) rotate(-120deg);
      }
      100% {
        transform: rotate(480deg) translateX(90px) rotate(-480deg);
      }
    }

    @keyframes orbit3 {
      0% {
        transform: rotate(240deg) translateX(90px) rotate(-240deg);
      }
      100% {
        transform: rotate(600deg) translateX(90px) rotate(-600deg);
      }
    }

    .tech-badge {
      width: 45px;
      height: 45px;
    }
  }
/* Fade in up animation */
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(40px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .logo-card {
      animation: fadeInUp 0.8s ease-out backwards;
    }

    .logo-card:nth-child(1) {
      animation-delay: 0.1s;
    }

    .logo-card:nth-child(2) {
      animation-delay: 0.2s;
    }

    .logo-card:nth-child(3) {
      animation-delay: 0.3s;
    }

    /* Shine effect */
    .shine-effect {
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
      transition: left 0.6s ease;
    }

    .logo-card:hover .shine-effect {
      left: 100%;
    }

    /* Floating animation */
    @keyframes float {
      0%, 100% {
        transform: translateY(0px);
      }
      50% {
        transform: translateY(-10px);
      }
    }

    .logo-card:hover {
      animation: float 3s ease-in-out infinite;
    }

    /* Glow effect */
    @keyframes glow {
      0%, 100% {
        box-shadow: 0 10px 40px rgba(249, 115, 22, 0.2);
      }
      50% {
        box-shadow: 0 10px 60px rgba(249, 115, 22, 0.4);
      }
    }

    .glow-orange {
      animation: glow 3s ease-in-out infinite;
    }

    @keyframes glowBlue {
      0%, 100% {
        box-shadow: 0 10px 40px rgba(37, 99, 235, 0.2);
      }
      50% {
        box-shadow: 0 10px 60px rgba(37, 99, 235, 0.4);
      }
    }

    .glow-blue {
      animation: glowBlue 3s ease-in-out infinite;
    }

.bubbles-container {
  overflow: hidden;
}

.bubble {
  position: absolute;
  bottom: -100px;
  left: var(--left);
  width: 40px;
  height: 40px;
  background: radial-gradient(circle at 30% 30%, rgba(249, 115, 22, 0.8), rgba(59, 130, 246, 0.4));
  border-radius: 50%;
  opacity: 0.6;
  animation: float-up var(--duration) ease-in infinite;
  animation-delay: var(--delay);
  box-shadow: 0 8px 16px rgba(249, 115, 22, 0.3), inset 0 -4px 8px rgba(255, 255, 255, 0.3);
  backdrop-filter: blur(5px);
}

.bubble::before {
  content: '';
  position: absolute;
  top: 10%;
  left: 15%;
  width: 40%;
  height: 40%;
  background: radial-gradient(circle, rgba(255, 255, 255, 0.8), transparent);
  border-radius: 50%;
}

@keyframes float-up {
  0% {
    bottom: -100px;
    opacity: 0;
    transform: translateX(0) scale(0.5);
  }
  10% {
    opacity: 0.8;
  }
  50% {
    transform: translateX(30px) scale(1);
  }
  100% {
    bottom: 110vh;
    opacity: 0;
    transform: translateX(-30px) scale(0.8);
  }
}

/* Floating Tech Icons */
.tech-icons-container {
  overflow: hidden;
}

.tech-icon-float {
  position: absolute;
  left: var(--icon-left);
  top: var(--icon-top);
  animation: float-tech-icon var(--icon-duration) ease-in-out infinite;
  animation-delay: var(--icon-delay);
}

.tech-icon-bubble {
  width: 50px;
  height: 50px;
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1), inset 0 2px 8px rgba(255, 255, 255, 0.2);
  border: 1px solid rgba(255, 255, 255, 0.2);
}

@keyframes float-tech-icon {
  0%, 100% {
    transform: translate(0, 0) rotate(0deg);
  }
  25% {
    transform: translate(20px, -20px) rotate(90deg);
  }
  50% {
    transform: translate(0, -40px) rotate(180deg);
  }
  75% {
    transform: translate(-20px, -20px) rotate(270deg);
  }
}

/* Particles Effect */
.particles-effect {
  overflow: hidden;
}

.particle {
  position: absolute;
  bottom: 0;
  left: var(--particle-left);
  width: 4px;
  height: 4px;
  background: linear-gradient(45deg, #f97316, #3b82f6, #8b5cf6);
  border-radius: 50%;
  animation: particle-float 4s ease-in-out infinite;
  animation-delay: var(--particle-delay);
  box-shadow: 0 0 10px rgba(249, 115, 22, 0.8);
}

@keyframes particle-float {
  0% {
    bottom: 0;
    opacity: 0;
  }
  10% {
    opacity: 1;
  }
  90% {
    opacity: 1;
  }
  100% {
    bottom: 100vh;
    opacity: 0;
  }
}

/* Enhanced Ring Animations */
@keyframes spin-slow {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}

@keyframes spin-reverse {
  from { transform: rotate(360deg); }
  to { transform: rotate(0deg); }
}

@keyframes pulse-scale {
  0%, 100% { transform: scale(1); }
  50% { transform: scale(1.1); }
}

@keyframes pulse-glow {
  0%, 100% { 
    opacity: 0.3;
    transform: scale(1);
  }
  50% { 
    opacity: 0.6;
    transform: scale(1.1);
  }
}

/* Progress Bar with Shimmer */
@keyframes progress {
  0% { width: 0%; }
  100% { width: 100%; }
}

@keyframes shimmer {
  0% { transform: translateX(-100%); }
  100% { transform: translateX(200%); }
}

/* Gradient Animation */
@keyframes gradient {
  0%, 100% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
}

/* Fade Animations */
@keyframes fade-in {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Bounce Animations */
@keyframes bounce-custom {
  0%, 80%, 100% { transform: scale(0); }
  40% { transform: scale(1); }
}

/* Animation Classes */
.animate-spin-slow {
  animation: spin-slow 3s linear infinite;
}

.animate-spin-reverse {
  animation: spin-reverse 2s linear infinite;
}

.animate-pulse-scale {
  animation: pulse-scale 2s ease-in-out infinite;
}

.animate-pulse-glow {
  animation: pulse-glow 2s ease-in-out infinite;
}

.animate-progress {
  animation: progress 2s ease-in-out;
}

.animate-shimmer {
  animation: shimmer 2s linear infinite;
}

.animate-gradient {
  background-size: 200% auto;
  animation: gradient 3s ease infinite;
}

.animate-fade-in {
  animation: fade-in 1s ease-out;
}

.animate-fade-in-delay {
  animation: fade-in 1s ease-out 0.5s backwards;
}

.animate-fade-in-delay-2 {
  animation: fade-in 1s ease-out 1s backwards;
}

.animate-bounce-delay-0 {
  animation: bounce-custom 1.4s infinite;
  animation-delay: 0s;
}

.animate-bounce-delay-1 {
  animation: bounce-custom 1.4s infinite;
  animation-delay: 0.2s;
}

.animate-bounce-delay-2 {
  animation: bounce-custom 1.4s infinite;
  animation-delay: 0.4s;
}

.preloader-hidden {
  opacity: 0;
  visibility: hidden;
  transition: opacity 0.6s ease, visibility 0.6s ease;
}
.loader-icon {
  width: 36px;
  height: 36px;
  object-fit: contain;
  position: relative;
  z-index: 20;
  animation: floatIcon 2.5s ease-in-out infinite;
}

/* Mobile adjustment */
@media (max-width: 640px) {
  .loader-icon {
    width: 32px;
    height: 32px;
  }
}

/* Floating animation */
@keyframes floatIcon {
  0% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(-6px);
  }
  100% {
    transform: translateY(0);
  }
}
  .stat-card {
    cursor: pointer;
  }

  .stat-card:hover .stat-number {
    animation: pulse-number 0.5s ease-in-out;
  }

  @keyframes pulse-number {
    0%, 100% {
      transform: scale(1);
    }
    50% {
      transform: scale(1.05);
    }
  }

  .stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
    transition: left 0.5s;
  }

  .stat-card:hover::before {
    left: 100%;
  }
  .stat-card:hover {
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
  }

  .stat-number {
    transition: transform 0.3s ease;
  }

 .navbar-logo {
    animation: glow-pulse 3s ease-in-out infinite;
  }

  @keyframes glow-pulse {
    0%, 100% {
      text-shadow: 0 0 10px rgba(59, 130, 246, 0.6),
                   0 0 20px rgba(59, 130, 246, 0.4),
                   0 0 30px rgba(59, 130, 246, 0.2);
    }
    50% {
      text-shadow: 0 0 20px rgba(59, 130, 246, 0.9),
                   0 0 35px rgba(59, 130, 246, 0.6),
                   0 0 50px rgba(59, 130, 246, 0.4);
    }
  }

  .navbar-logo-accent {
    animation: orange-glow-pulse 3s ease-in-out infinite;
    animation-delay: 0.5s;
  }

  @keyframes orange-glow-pulse {
    0%, 100% {
      text-shadow: 0 0 10px rgba(249, 115, 22, 0.6),
                   0 0 20px rgba(249, 115, 22, 0.4);
    }
    50% {
      text-shadow: 0 0 20px rgba(249, 115, 22, 0.9),
                   0 0 35px rgba(249, 115, 22, 0.6),
                   0 0 50px rgba(249, 115, 22, 0.4);
    }
  }

  .navbar-logo:hover {
    animation: glow-pulse-fast 1s ease-in-out infinite;
  }

  @keyframes glow-pulse-fast {
    0%, 100% {
      text-shadow: 0 0 15px rgba(59, 130, 246, 0.8),
                   0 0 30px rgba(59, 130, 246, 0.6),
                   0 0 45px rgba(59, 130, 246, 0.4);
      transform: scale(1);
    }
    50% {
      text-shadow: 0 0 25px rgba(59, 130, 246, 1),
                   0 0 45px rgba(59, 130, 246, 0.8),
                   0 0 65px rgba(59, 130, 246, 0.6);
      transform: scale(1.02);
    }
  }

  .navbar-logo:hover .navbar-logo-accent {
    animation: orange-glow-pulse-fast 1s ease-in-out infinite;
  }

  @keyframes orange-glow-pulse-fast {
    0%, 100% {
      text-shadow: 0 0 15px rgba(249, 115, 22, 0.8),
                   0 0 30px rgba(249, 115, 22, 0.6);
    }
    50% {
      text-shadow: 0 0 25px rgba(249, 115, 22, 1),
                   0 0 45px rgba(249, 115, 22, 0.8);
    }
  }
  @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700;900&display=swap');

  </style>
</head>

<body class="bg-gray-50 text-gray-800">
  <div id="preloader" class="fixed inset-0 z-[9999] bg-gradient-to-br from-[#0B2540] via-[#1E3A8A] to-[#0B2540] flex items-center justify-center overflow-hidden">
  <!-- Animated Background -->
  <div class="absolute inset-0 overflow-hidden">
    <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-orange-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse"></div>
    <div class="absolute top-1/3 right-1/4 w-96 h-96 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse" style="animation-delay: 2s;"></div>
    <div class="absolute bottom-1/4 left-1/2 w-96 h-96 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse" style="animation-delay: 4s;"></div>
  </div>

  <!-- Floating Bubbles Container -->
  <div class="bubbles-container absolute inset-0 pointer-events-none">
    <div class="bubble" style="--delay: 0s; --duration: 8s; --left: 10%;"></div>
    <div class="bubble" style="--delay: 1s; --duration: 10s; --left: 20%;"></div>
    <div class="bubble" style="--delay: 2s; --duration: 7s; --left: 30%;"></div>
    <div class="bubble" style="--delay: 0.5s; --duration: 9s; --left: 40%;"></div>
    <div class="bubble" style="--delay: 1.5s; --duration: 11s; --left: 50%;"></div>
    <div class="bubble" style="--delay: 2.5s; --duration: 8s; --left: 60%;"></div>
    <div class="bubble" style="--delay: 0.8s; --duration: 10s; --left: 70%;"></div>
    <div class="bubble" style="--delay: 1.8s; --duration: 9s; --left: 80%;"></div>
    <div class="bubble" style="--delay: 2.2s; --duration: 7s; --left: 90%;"></div>
    <div class="bubble" style="--delay: 3s; --duration: 12s; --left: 15%;"></div>
    <div class="bubble" style="--delay: 0.3s; --duration: 8s; --left: 35%;"></div>
    <div class="bubble" style="--delay: 1.2s; --duration: 10s; --left: 55%;"></div>
    <div class="bubble" style="--delay: 2.8s; --duration: 9s; --left: 75%;"></div>
    <div class="bubble" style="--delay: 0.6s; --duration: 11s; --left: 85%;"></div>
    <div class="bubble" style="--delay: 1.6s; --duration: 7s; --left: 25%;"></div>
  </div>

  <!-- Floating Tech Icons -->
  <div class="tech-icons-container absolute inset-0 pointer-events-none">
    <div class="tech-icon-float" style="--icon-delay: 0s; --icon-duration: 6s; --icon-left: 15%; --icon-top: 20%;">
      <div class="tech-icon-bubble">
        <svg class="w-6 h-6 text-orange-400" fill="currentColor" viewBox="0 0 24 24">
          <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
        </svg>
      </div>
    </div>
    <div class="tech-icon-float" style="--icon-delay: 1s; --icon-duration: 7s; --icon-left: 75%; --icon-top: 30%;">
      <div class="tech-icon-bubble">
        <svg class="w-6 h-6 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
          <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
        </svg>
      </div>
    </div>
    <div class="tech-icon-float" style="--icon-delay: 2s; --icon-duration: 8s; --icon-left: 85%; --icon-top: 70%;">
      <div class="tech-icon-bubble">
        <svg class="w-6 h-6 text-purple-400" fill="currentColor" viewBox="0 0 24 24">
          <path d="M13 10V3L4 14h7v7l9-11h-7z"/>
        </svg>
      </div>
    </div>
    <div class="tech-icon-float" style="--icon-delay: 0.5s; --icon-duration: 7.5s; --icon-left: 20%; --icon-top: 75%;">
      <div class="tech-icon-bubble">
        <svg class="w-6 h-6 text-orange-400" fill="currentColor" viewBox="0 0 24 24">
          <path d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
        </svg>
      </div>
    </div>
    <div class="tech-icon-float" style="--icon-delay: 1.5s; --icon-duration: 6.5s; --icon-left: 10%; --icon-top: 50%;">
      <div class="tech-icon-bubble">
        <svg class="w-6 h-6 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
          <path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
          <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
      </div>
    </div>
    <div class="tech-icon-float" style="--icon-delay: 2.5s; --icon-duration: 7s; --icon-left: 90%; --icon-top: 15%;">
      <div class="tech-icon-bubble">
        <svg class="w-6 h-6 text-purple-400" fill="currentColor" viewBox="0 0 24 24">
          <path d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
      </div>
    </div>
  </div>

  <!-- Particles Effect -->
  <div class="particles-effect absolute inset-0 pointer-events-none">
    <div class="particle" style="--particle-delay: 0s; --particle-left: 5%;"></div>
    <div class="particle" style="--particle-delay: 0.5s; --particle-left: 25%;"></div>
    <div class="particle" style="--particle-delay: 1s; --particle-left: 45%;"></div>
    <div class="particle" style="--particle-delay: 1.5s; --particle-left: 65%;"></div>
    <div class="particle" style="--particle-delay: 2s; --particle-left: 85%;"></div>
    <div class="particle" style="--particle-delay: 0.3s; --particle-left: 15%;"></div>
    <div class="particle" style="--particle-delay: 0.8s; --particle-left: 35%;"></div>
    <div class="particle" style="--particle-delay: 1.3s; --particle-left: 55%;"></div>
    <div class="particle" style="--particle-delay: 1.8s; --particle-left: 75%;"></div>
    <div class="particle" style="--particle-delay: 2.3s; --particle-left: 95%;"></div>
  </div>

  <!-- Loader Content -->
  <div class="relative z-10 text-center">
    <!-- Spinning Tech Rings with Glow -->
    <div class="relative w-40 h-40 mx-auto mb-8">
      <!-- Outer Glow -->
      <div class="absolute inset-0 bg-orange-500 rounded-full filter blur-2xl opacity-30 animate-pulse-glow"></div>
      
      <!-- Outer Ring -->
      <div class="absolute inset-0 border-4 border-orange-500 border-t-transparent rounded-full animate-spin-slow shadow-2xl shadow-orange-500/50"></div>
      <!-- Middle Ring -->
      <div class="absolute inset-3 border-4 border-blue-500 border-b-transparent rounded-full animate-spin-reverse shadow-xl shadow-blue-500/50"></div>
      <!-- Inner Ring -->
      <div class="absolute inset-6 border-4 border-purple-500 border-l-transparent rounded-full animate-spin-slow shadow-lg shadow-purple-500/50"></div>
      
      <!-- Center Icon with Pop Effect -->
      <div class="absolute inset-0 flex items-center justify-center">
        <div class="w-16 h-16  rounded-2xl flex items-center justify-center animate-pulse-scale shadow-2xl relative">
          <!-- Icon Glow -->
          <div class="absolute inset-0 rounded-2xl filter blur-md opacity-50 animate-pulse"></div>
         <img src="images/image-removebg.png"  alt="AI Icon" class="moving-icon"/>
        </div>
      </div>
    </div>

    <!-- Loading Text with Gradient -->
    <h2 class="text-4xl font-bold text-white mb-4 animate-fade-in">
      Emerging<span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 via-orange-500 to-orange-600 animate-gradient">Tech</span>
    </h2>
    
    <!-- Progress Bar with Shimmer -->
    <div class="w-64 h-2 bg-white/10 rounded-full overflow-hidden mx-auto mb-4 backdrop-blur-sm relative">
      <div class="h-full bg-gradient-to-r from-orange-500 via-blue-500 to-purple-500 rounded-full animate-progress relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white to-transparent opacity-50 animate-shimmer"></div>
      </div>
    </div>
    
    <!-- Loading Dots with Enhanced Animation -->
    <div class="flex justify-center space-x-2 mb-2">
      <div class="w-3 h-3 bg-orange-500 rounded-full animate-bounce-delay-0 shadow-lg shadow-orange-500/50"></div>
      <div class="w-3 h-3 bg-blue-500 rounded-full animate-bounce-delay-1 shadow-lg shadow-blue-500/50"></div>
      <div class="w-3 h-3 bg-purple-500 rounded-full animate-bounce-delay-2 shadow-lg shadow-purple-500/50"></div>
    </div>
    
    <p class="text-gray-300 mt-4 animate-fade-in-delay text-lg">Loading amazing experiences...</p>
    
    <!-- Percentage Counter -->
    <div class="mt-3">
      <span id="loadingPercentage" class="text-orange-400 font-bold text-xl animate-fade-in-delay-2">0%</span>
    </div>
  </div>
</div>

  <!-- Body Particles Background -->
  <div id="body-particles"></div>
 
  <!-- Mobile Menu Overlay -->
  <div class="mobile-overlay md:hidden" id="mobileOverlay"></div>
 
  <!-- Mobile Menu -->
  <div class="mobile-menu md:hidden" id="mobileMenu">
    <div>
      <h2 class="text-2xl font-bold text-white mb-4">EmergingTechnologies</h2>
    </div>
    {{-- <div>
      <h3 style="font-weight:bold; color:#d7dadb; width:100%; text-align:center; align-items:center; margin:auto;">
        Statewide Training & Awareness Camps on Emerging Technologies</h3>
    </div> --}}
    <hr class="my-6 border-gray-300">
    <ul class="flex flex-col space-y-6 text-white">
      <li><a href="#" class="text-lg hover:text-orange-500 transition">Home</a></li>
      <li><a href="https://www.ocac.in/" target="_blank" class="text-lg hover:text-orange-500 transition">OCAC</a></li>
      <li><a href="#courses" class="text-lg hover:text-orange-500 transition">Camps</a></li>
      <li><a href="#contact" class="text-lg hover:text-orange-500 transition">Contact</a></li>
      <li><a href="#" class="bg-orange-500 hover:bg-orange-600 px-6 py-2 rounded-full font-semibold text-white shadow-lg transition transform hover:scale-105 inline-block">Login</a></li>
    </ul>
  </div>

  <!-- Navbar -->
  <nav id="navbar"
    class="fixed top-0 left-0 w-full z-50 bg-gradient-to-tr from-[#081C33] to-[#1E3A8A] text-white px-4 sm:px-6 lg:pl-24 lg:pr-20 py-4 flex justify-between items-center transition-all duration-500 shadow-lg">
    <h1 class="navbar-logo text-xl sm:text-2xl font-bold
         text-blue-200
         tracking-wide">
    Emerging <span class="text-orange-400 navbar-logo-accent">Technologies</span>
  </h1>

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
            <a href="{{ url('/dashboard') }}"
              class="bg-orange-500 hover:bg-orange-600 px-6 py-2 rounded-full font-semibold text-white shadow-lg transition transform hover:scale-105">Dashboard</a>
          @else
            <a href="{{ route('login') }}"
              class="bg-orange-500 hover:bg-orange-600 px-6 py-2 rounded-full font-semibold text-white shadow-lg transition transform hover:scale-105">Login</a>
          @endauth
        </nav>
      @endif
      {{-- <a href="#"
        class="bg-orange-500 hover:bg-orange-600 px-6 py-2 rounded-full font-semibold text-white shadow-lg transition transform hover:scale-105">Login</a>
      --}}
    </div>

    <!-- Mobile Menu Button -->
    <button id="menuToggle" class="md:hidden text-white focus:outline-none">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
      </svg>
    </button>
  </nav>

  <section
    class="relative bg-[#0B2540] text-white min-h-[500px] sm:min-h-[600px] py-20 pt-32 px-4 sm:px-6 lg:px-16 flex items-center overflow-hidden">
    <!-- Particles.js Background -->
    <div id="particles-js"></div>

    <!-- Animated Background Pattern -->
    <div class="absolute inset-0 opacity-10 z-0">
      <div
        class="absolute top-0 left-0 w-96 h-96 bg-orange-500 rounded-full mix-blend-multiply filter blur-3xl animate-pulse">
      </div>
      <div
        class="absolute top-0 right-0 w-96 h-96 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl animate-pulse"
        style="animation-delay: 2s;"></div>
      <div
        class="absolute bottom-0 left-1/2 w-96 h-96 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl animate-pulse"
        style="animation-delay: 4s;"></div>
    </div>

    <!-- Blue Overlay -->
    <div class="absolute inset-0 bg-gradient-to-br from-[#0B2540]/90 via-[#1E3A8A]/80 to-[#0B2540]/90"></div>

    <!-- Content -->
    <div class="relative container mx-auto grid md:grid-cols-2 gap-8 items-center z-10">
      <div class="space-y-6 text-center md:text-left">
        <h2 class="text-2xl sm:text-3xl lg:text-4xl xl:text-5xl font-bold leading-tight">
          Training Camps on
          <span class="gradient-text block mt-2 h-20">
            <span id="typing"></span><span class="animate-blink">|</span>
          </span>
        </h2>
        <p class="text-base sm:text-lg text-gray-300 max-w-xl mx-auto md:mx-0">
          Discover the latest innovations in Artificial Intelligence, IoT & Robotics, and CyberSecurity. Empower your
          career with cutting-edge skills.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
          <a href="#courses"
            class="bg-orange-500 hover:bg-orange-600 px-8 py-3 rounded-full font-semibold text-white shadow-lg transition transform hover:scale-105 pulse-button">
            Explore Camps
          </a>
          {{-- <a href="#about"
            class="glass px-8 py-3 rounded-full font-semibold text-white hover:bg-white/25 transition">
            Learn More
          </a> --}}
        </div>
      </div>

      <!-- Image on Right with Orbiting Icons -->
      <div class="banner-image-container">
        <!-- Glow effect behind image -->
        <div
          class="absolute w-72 h-72 sm:w-96 sm:h-96 lg:w-[450px] lg:h-[450px] bg-orange-500 rounded-full blur-3xl opacity-20">
        </div>

        <!-- Main Image -->
        <img src="images/image-removebg.png" alt="EmergingTech"
          class="banner-image floating w-64 h-80 sm:w-80 sm:h-80 lg:w-96 lg:h-96 object-contain" />

        <!-- Orbiting Tech Icons Container -->
        <div class="orbiting-icons-container">
          <!-- AI Icon -->
          <div class="orbit-icon orbit-icon-1">
            <div class="tech-badge ">
              <img src="images\image8.png" alt="AI" class="w-full h-full object-cover rounded-full">
            </div>
            {{-- <span class="tech-label">AI</span> --}}
          </div>

          <!-- IoT & Robotics Icon -->
          <div class="orbit-icon orbit-icon-2">
            <div class="tech-badge">
              <img src="images\iot.png" alt="IoT & Robotics" class="w-full h-full object-cover rounded-full">
            </div>
            {{-- <span class="tech-label">IoT</span> --}}
          </div>

          <!-- Cybersecurity Icon -->
          <div class="orbit-icon orbit-icon-3">
            <div class="tech-badge ">
              <img src="images\cyber_security.png" alt="Cybersecurity" class="w-full h-full object-cover rounded-full">
            </div>
            {{-- <span class="tech-label">Security</span> --}}
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- About Section - Enhanced with Emerging Tech Focus -->
  <section id="about" class="py-20 px-4 sm:px-6 lg:px-12 bg-gradient-to-br from-[#F5F7FA] to-[#E8F0FE]">
    <div class="max-w-7xl mx-auto">
      <!-- Section Header -->
      <div class="text-center mb-16 reveal">
        <h2 class="text-4xl sm:text-5xl font-bold text-[#0B2540] mb-4 section-title">About</h2>
        <p class="text-lg text-gray-600 max-w-3xl mx-auto mt-8">
          Pioneering the future of education through cutting-edge technology training
        </p>
      </div>

      <!-- Main Content Grid -->
      <div class="grid lg:grid-cols-2 gap-12 items-center mb-16">
        <!-- Left: Mission Statement -->
        <div class="reveal slide-in-left">
          <div
            class="bg-white rounded-3xl p-8 sm:p-10 shadow-2xl hover:shadow-3xl transition-all duration-500 border-l-4 border-orange-500">
            <div class="flex items-center mb-6">
              <div
                class="w-16 h-16 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl flex items-center justify-center mr-4 tech-icon">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z">
                  </path>
                </svg>
              </div>
              <h3 class="text-2xl sm:text-3xl font-bold text-[#0B2540]">Our Vision</h3>
            </div>
            <p class="text-gray-700 text-lg leading-relaxed mb-6">
              <span class="font-bold text-orange-500">EmergingTech</span> is at the forefront of technological
              education, dedicated to preparing the next generation for an AI-driven future. We deliver comprehensive
              training programs that bridge the gap between academia and industry demands.
            </p>
            <p class="text-gray-700 text-lg leading-relaxed">
              In partnership with <a href="https://okcl.org/" target="_blank"
                class="font-bold text-blue-600 hover:text-blue-700 transition">OKCL</a> as our <span
                class="font-semibold text-blue-600">Implementation Partner</span>, we're transforming education across
              Odisha through innovative, hands-on learning experiences.
            </p>
          </div>
        </div>

        <!-- Right: Why Choose Us -->
        <div class="reveal slide-in-right">
          <div class="space-y-4">
            <!-- Feature Card 1 -->
            <div
              class="bg-gradient-to-r from-orange-50 to-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-orange-100">
              <div class="flex items-start">
                <div class="w-12 h-12 bg-orange-500 rounded-xl flex items-center justify-center mr-4 flex-shrink-0">
                  <span class="text-2xl">🤖</span>
                </div>
                <div>
                  <h4 class="text-xl font-bold text-[#0B2540] mb-2">AI Discovery</h4>
                  <p class="text-gray-600"> Learn how machines think and learn through neural networks and AI tools, and
                    build smart solutions used in today’s digital world.</p>
                </div>
              </div>
            </div>

            <!-- Feature Card 2 -->
            <div
              class="bg-gradient-to-r from-blue-50 to-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-blue-100">
              <div class="flex items-start">
                <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center mr-4 flex-shrink-0">
                  <span class="text-2xl">⚙️</span>
                </div>
                <div>
                  <h4 class="text-xl font-bold text-[#0B2540] mb-2">IOT & Robotics Innovation</h4>
                  <p class="text-gray-600"> Understand how to protect systems, networks, and data from cyber threats
                    through secure practices, risk management, and digital defense strategies</p>
                </div>
              </div>
            </div>

            <!-- Feature Card 3 -->
            <div
              class="bg-gradient-to-r from-purple-50 to-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-purple-100">
              <div class="flex items-start">
                <div class="w-12 h-12 bg-purple-600 rounded-xl flex items-center justify-center mr-4 flex-shrink-0">
                  <span class="text-2xl">🔒</span>
                </div>
                <div>
                  <h4 class="text-xl font-bold text-[#0B2540] mb-2">Cybersecurity Awareness</h4>
                  <p class="text-gray-600">Defend against cyber threats with advanced security protocols and ethical
                    hacking techniques</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <br>
      <!-- Stats Section -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-16">
        <!-- District Card -->
        <div
          class="reveal stat-card bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 text-center border-t-4 border-orange-500 relative overflow-hidden group">
          <div
            class="absolute inset-0 bg-gradient-to-br from-orange-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500">
          </div>
          <div class="relative z-10">
            <div class="text-5xl font-bold text-orange-500 mb-2 stat-number" data-target="30" data-suffix="">30</div>
            <p class="text-gray-600 font-medium">District</p>
          </div>
          <div
            class="absolute -bottom-2 -right-2 w-20 h-20 bg-orange-500 rounded-full opacity-10 group-hover:scale-150 transition-transform duration-500">
          </div>
        </div>

        <!-- Institutions Card -->
        <div
          class="reveal stat-card bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 text-center border-t-4 border-blue-600 relative overflow-hidden group">
          <div
            class="absolute inset-0 bg-gradient-to-br from-blue-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500">
          </div>
          <div class="relative z-10">
            <div class="text-5xl font-bold text-blue-600 mb-2 stat-number" data-target="100" data-suffix="+">100+</div>
            <p class="text-gray-600 font-medium">Institutions</p>
          </div>
          <div
            class="absolute -bottom-2 -right-2 w-20 h-20 bg-blue-600 rounded-full opacity-10 group-hover:scale-150 transition-transform duration-500">
          </div>
        </div>

        <!-- Students Card -->
        <div
          class="reveal stat-card bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 text-center border-t-4 border-orange-500 relative overflow-hidden group">
          <div
            class="absolute inset-0 bg-gradient-to-br from-orange-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500">
          </div>
          <div class="relative z-10">
            <div class="text-5xl font-bold text-orange-500 mb-2 stat-number" data-target="120" data-suffix="">120</div>
            <p class="text-gray-600 font-medium">Student per Camp</p>
          </div>
          <div
            class="absolute -bottom-2 -right-2 w-20 h-20 bg-orange-500 rounded-full opacity-10 group-hover:scale-150 transition-transform duration-500">
          </div>
        </div>

        <!-- Tech Gadget Card -->
        <div
          class="reveal stat-card bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 text-center border-t-4 border-blue-600 relative overflow-hidden group">
          <div
            class="absolute inset-0 bg-gradient-to-br from-blue-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500">
          </div>
          <div class="relative z-10">
            <div class="text-5xl font-bold text-blue-600 mb-2 stat-number" data-target="50" data-suffix="%">50%</div>
            <p class="text-gray-600 font-medium">Emerging Tech Gadget</p>
          </div>
          <div
            class="absolute -bottom-2 -right-2 w-20 h-20 bg-blue-600 rounded-full opacity-10 group-hover:scale-150 transition-transform duration-500">
          </div>
        </div>
      </div>

  </section>
  <!-- Courses Section -->
  <section id="courses" class="py-20 px-4 sm:px-6 lg:px-12 bg-gradient-to-br from-[#E8F0FE] to-[#F5F7FA]">
    <div class="max-w-7xl mx-auto">
      <div class="text-center mb-12 reveal">
        <h2 class="text-3xl sm:text-4xl font-bold text-[#0B2540] mb-4 section-title">Our Training Camps</h2>
        <div class="w-20 h-1 bg-gradient-to-r from-orange-500 to-orange-300 mx-auto rounded-full"></div>
        <p class="mt-4 text-gray-600 max-w-2xl mx-auto">Master the technologies that are shaping tomorrow's world</p>
      </div>

      <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
        <!-- AI Card -->
        <div
          class="card reveal bg-white rounded-2xl shadow-xl hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300 overflow-hidden group">
          <div class="overflow-hidden">
            <img src="https://images.unsplash.com/photo-1677442136019-21780ecad995?w=600&h=400&fit=crop"
              alt="Artificial Intelligence" class="w-full h-48 sm:h-56 object-cover card-image" />
          </div>
          <div class="p-6 sm:p-8">
            <div class="flex items-center mb-4">
              <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mr-4">
                <span class="text-2xl">🤖</span>
              </div>
              <h3 class="text-xl sm:text-2xl font-bold text-[#0B2540]">AI Discovery</h3>
            </div>
            <p class="text-gray-600 mb-4">
              Learn the fundamentals and advanced concepts of AI, from machine learning to deep learning applications.
            </p>
            {{-- <a href="#"
              class="inline-flex items-center text-orange-500 hover:text-orange-600 font-semibold transition group-hover:translate-x-2 transform duration-300">
              Learn More
              <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
              </svg>
            </a> --}}
          </div>
        </div>

        <!-- Robotics Card -->
        <div
          class="card reveal bg-white rounded-2xl shadow-xl hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300 overflow-hidden group">
          <div class="overflow-hidden">
            <img src="https://images.unsplash.com/photo-1561557944-6e7860d1a7eb?w=600&h=400&fit=crop" alt="Robotics"
              class="w-full h-48 sm:h-56 object-cover card-image" />
          </div>
          <div class="p-6 sm:p-8">
            <div class="flex items-center mb-4">
              <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mr-4">
                <span class="text-2xl">⚙️</span>
              </div>
              <h3 class="text-xl sm:text-2xl font-bold text-[#0B2540]">IOT & Robotics Innovation</h3>
            </div>
            <p class="text-gray-600 mb-4">
              Dive into the exciting world of robotics, automation, and intelligent machines that shape industries.
            </p>
            {{-- <a href="#"
              class="inline-flex items-center text-orange-500 hover:text-orange-600 font-semibold transition group-hover:translate-x-2 transform duration-300">
              Learn More
              <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
              </svg>
            </a> --}}
          </div>
        </div>

        <!-- Cyber Security Card -->
        <div class="card reveal bg-white rounded-2xl shadow-xl hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300 overflow-hidden group">
          <div class="overflow-hidden">
            <img src="https://images.unsplash.com/photo-1550751827-4bd374c3f58b?w=600&h=400&fit=crop"
              alt="Cyber Security" class="w-full h-48 sm:h-56 object-cover card-image" />
          </div>
          <div class="p-6 sm:p-8">
            <div class="flex items-center mb-4">
              <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mr-4">
                <span class="text-2xl">🔒</span>
              </div>
              <h3 class="text-xl sm:text-2xl font-bold text-[#0B2540]">Cybersecurity Awareness</h3>
            </div>
            <p class="text-gray-600 mb-4">
              Protect the digital world by mastering cyber defense strategies and security protocols.
            </p>
            {{-- <a href="#"
              class="inline-flex items-center text-orange-500 hover:text-orange-600 font-semibold transition group-hover:translate-x-2 transform duration-300">
              Learn More
              <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
              </svg>
            </a> --}}
          </div>
        </div>
      </div>
    </div>
  </section>
  <section
    class="py-20 px-4 sm:px-6 lg:px-12 bg-gradient-to-br from-white via-gray-50 to-blue-50 relative overflow-hidden">
    <!-- Animated background elements -->
    <div class="absolute inset-0 opacity-5">
      <div class="absolute top-10 left-10 w-96 h-96 bg-orange-500 rounded-full blur-3xl animate-pulse"></div>
      <div class="absolute bottom-10 right-10 w-96 h-96 bg-blue-500 rounded-full blur-3xl animate-pulse"
        style="animation-delay: 1.5s;"></div>
      <div
        class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-purple-500 rounded-full blur-3xl animate-pulse"
        style="animation-delay: 3s;"></div>
    </div>

    <div class="max-w-7xl mx-auto relative z-10">
      <!-- Section Title -->
      <div class="text-center mb-16 reveal active">
        <h2 class="text-4xl sm:text-5xl font-extrabold text-[#0B2540] mb-4">
          Our <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-orange-600">Partners</span>
        </h2>
        <div class="w-24 h-1.5 bg-gradient-to-r from-orange-500 via-orange-400 to-orange-500 mx-auto rounded-full">
        </div>
        <p class="text-gray-600 mt-6 text-lg max-w-2xl mx-auto">Collaborating with industry leaders to deliver
          excellence</p>
      </div>
      <!-- Logos Grid -->
      <div class="grid md:grid-cols-3 gap-8 lg:gap-10 max-w-6xl mx-auto">

        <!-- Logo Card 1 - Odisha Govt -->
        <div class="logo-card group">
          <div
            class="relative bg-gradient-to-br from-orange-50 via-white to-orange-50 rounded-3xl p-8 shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-3 border-2 border-orange-100 hover:border-orange-500 overflow-hidden glow-orange h-[280px] flex flex-col items-center justify-center">
            <div class="shine-effect"></div>

            <div class="relative z-10 text-center w-full">
              <div class="mb-4 transform group-hover:scale-110 transition-transform duration-500">
                <img src="images/odisha-logo.png" alt="Partner Logo"
                  class="h-20 w-auto object-contain mx-auto filter grayscale group-hover:grayscale-0 transition-all duration-500" />
              </div>
              <p class="text-base text-gray-800 font-bold mb-2">Electronics & IT Department</p>
              <p class="text-sm text-gray-700 font-semibold">Government of Odisha</p>

              <div
                class="mt-4 space-y-1 opacity-0 group-hover:opacity-100 transition-all duration-500 transform translate-y-2 group-hover:translate-y-0">
                <div class="w-12 h-0.5 bg-gradient-to-r from-orange-500 to-orange-400 mx-auto rounded-full"></div>
                <p class="text-xs text-gray-600 font-medium">Supporting Partner</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Logo Card 2 - OCAC -->
        <div class="logo-card group">
          <div
            class="relative bg-gradient-to-br from-orange-50 via-white to-orange-50 rounded-3xl p-8 shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-3 border-2 border-orange-100 hover:border-orange-500 overflow-hidden glow-orange h-[280px] flex flex-col items-center justify-center">
            <div class="shine-effect"></div>

            <div class="relative z-10 text-center w-full">
              <div class="mb-4 transform group-hover:scale-110 transition-transform duration-500">
                <img src="images/ocac-logo.png" alt="OCAC Logo"
                  class="h-20 w-auto object-contain mx-auto filter drop-shadow-lg" />
              </div>

              <div
                class="space-y-2 opacity-0 group-hover:opacity-100 transition-all duration-500 transform translate-y-2 group-hover:translate-y-0">
                <p class="font-bold text-lg text-gray-800">OCAC</p>
                <div class="w-12 h-0.5 bg-gradient-to-r from-orange-500 to-orange-400 mx-auto rounded-full"></div>
                <p class="text-xs text-gray-600 font-medium">Supporting Partner</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Logo Card 3 - OKCL -->
        <div class="logo-card group">
          <div
            class="relative bg-gradient-to-br from-orange-50 via-white to-orange-50 rounded-3xl p-8 shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-3 border-2 border-orange-100 hover:border-orange-500 overflow-hidden glow-orange h-[280px] flex flex-col items-center justify-center">
            <div class="shine-effect"></div>

            <div class="relative z-10 text-center w-full">
              <div class="mb-4 transform group-hover:scale-110 transition-transform duration-500">
                <img src="images/okcl logo_HD.png" alt="OKCL Logo"
                  class="h-28 w-auto object-contain mx-auto filter drop-shadow-lg" />
              </div>

              <div
                class="space-y-2 opacity-0 group-hover:opacity-100 transition-all duration-500 transform translate-y-2 group-hover:translate-y-0">
                <p class="font-bold text-lg text-gray-800">OKCL</p>
                <div class="w-12 h-0.5 bg-gradient-to-r from-blue-600 to-blue-500 mx-auto rounded-full"></div>
                <p class="text-xs text-gray-600 font-medium">Implementation Partner</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Decorative Divider -->
      <div class="mt-20 flex items-center justify-center">
        <div class="flex-1 h-px bg-gradient-to-r from-transparent via-orange-500 to-transparent max-w-md"></div>
        <div class="mx-6">
          <div class="relative">
            <div class="w-4 h-4 bg-orange-500 rounded-full animate-pulse"></div>
            <div class="absolute inset-0 w-4 h-4 bg-orange-500 rounded-full animate-ping opacity-75"></div>
          </div>
        </div>
        <div class="flex-1 h-px bg-gradient-to-r from-transparent via-blue-600 to-transparent max-w-md"></div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer id="contact" class="bg-gradient-to-br from-[#0B2540] to-[#1E3A8A] text-white py-12 px-4 sm:px-6 lg:px-12">
    <div class="max-w-7xl mx-auto">
      <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8 mb-8">
        <div class="reveal">
          <img class="w-48 mb-4" src="{{ asset('images\ocac.png') }}" alt="OCAC" />
          <p class="text-gray-300 leading-relaxed">
            Odisha Computer Application Centre (OCAC), the Technical Directorate of Electronics & Information Technology
            Department, Government of Odisha, has evolved through years as a centre of excellence in IT solutions and
            e-Governance...
            <a href="https://www.ocac.in/" target="_blank" class="text-orange-400 hover:text-orange-300 transition">Read
              More</a>
          </p>
        </div>

        <div class="reveal sm:ml-8 lg:ml-14">
          <h3 class="text-xl font-bold mb-6 flex items-center">
            <span class="w-1 h-6 bg-orange-500 mr-3 rounded"></span>
            Quick Links
          </h3>
          <ul class="space-y-3">
            <li><a href="#" class="hover:text-orange-500 transition flex items-center group"><span
                  class="mr-2 group-hover:translate-x-1 transition-transform">→</span>Home</a></li>
            <li><a href="#about" class="hover:text-orange-500 transition flex items-center group"><span
                  class="mr-2 group-hover:translate-x-1 transition-transform">→</span>About</a></li>
            <li><a href="#courses" class="hover:text-orange-500 transition flex items-center group"><span
                  class="mr-2 group-hover:translate-x-1 transition-transform">→</span>Courses</a></li>
            <li><a href="https://okcl.org" target="_blank"
                class="hover:text-orange-500 transition flex items-center group"><span
                  class="mr-2 group-hover:translate-x-1 transition-transform">→</span>OKCL</a></li>
            <li><a href="#contact" class="hover:text-orange-500 transition flex items-center group"><span
                  class="mr-2 group-hover:translate-x-1 transition-transform">→</span>Contact</a></li>
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                </path>
              </svg>
              +91 6743520021
            </p>
          </div>

          <div class="mt-6">
            <h4 class="font-semibold mb-3">Follow Us</h4>
            <div class="flex space-x-4">
              <a href="https://x.com/odisha_okcl" target="_blank"
                class="w-10 h-10 bg-white/10 hover:bg-orange-500 rounded-full flex items-center justify-center transition transform hover:scale-110">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                  <path
                    d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z" />
                </svg>
              </a>
              <a href="https://www.facebook.com/OKCLed/" target="_blank"
                class="w-10 h-10 bg-white/10 hover:bg-orange-500 rounded-full flex items-center justify-center transition transform hover:scale-110">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                  <path
                    d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z" />
                </svg>
              </a>
              <a href="https://www.linkedin.com/company/odisha-knowledge-corporation-limited/posts/?feedView=all"
                target="_blank"
                class="w-10 h-10 bg-white/10 hover:bg-orange-500 rounded-full flex items-center justify-center transition transform hover:scale-110">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                  <path
                    d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" />
                </svg>
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
  <button id="backToTop"
    class="fixed bottom-6 right-6 z-50 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white rounded-full p-4 shadow-2xl transition-all opacity-0 pointer-events-none transform hover:scale-110"
    aria-label="Back to Top" onclick="window.scrollTo({top: 0, behavior: 'smooth'});">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
    </svg>
  </button>

  <script>
    // Initialize Body Particles.js
   particlesJS('body-particles', {
      particles: {
        number: {
          value: 80,
          density: {
            enable: true,
            value_area: 1000
          }
        },
        color: {
          value: ['#f97316', '#3b82f6', '#8b5cf6']
        },
        shape: {
          type: 'circle'
        },
        opacity: {
          value: 0.6,
          random: true,
          anim: {
            enable: true,
            speed: 0.8,
            opacity_min: 0.3,
            sync: false
          }
        },
        size: {
          value: 5,
          random: true,
          anim: {
            enable: true,
            speed: 2,
            size_min: 1,
            sync: false
          }
        },
        line_linked: {
          enable: true,
          distance: 150,
          color: '#3b82f6',
          opacity: 0.4,
          width: 1.5
        },
        move: {
          enable: true,
          speed: 1.5,
          direction: 'none',
          random: true,
          straight: false,
          out_mode: 'out',
          bounce: false
        }
      },
      interactivity: {
        detect_on: 'canvas',
        events: {
          onhover: {
            enable: true,
            mode: 'grab'
          },
          resize: true
        },
        modes: {
          grab: {
            distance: 140,
            line_linked: {
              opacity: 0.6
            }
          }
        }
      },
      retina_detect: true
    });

    // Initialize Banner Particles.js with increased opacity
    particlesJS('particles-js', {
      particles: {
        number: {
          value: 100,
          density: {
            enable: true,
            value_area: 800
          }
        },
        color: {
          value: ['#f97316', '#fb923c', '#60a5fa']
        },
        shape: {
          type: 'circle',
          stroke: {
            width: 0,
            color: '#000000'
          }
        },
        opacity: {
          value: 0.7,
          random: true,
          anim: {
            enable: true,
            speed: 1,
            opacity_min: 0.3,
            sync: false
          }
        },
        size: {
          value: 4,
          random: true,
          anim: {
            enable: true,
            speed: 2,
            size_min: 0.5,
            sync: false
          }
        },
        line_linked: {
          enable: true,
          distance: 150,
          color: '#fb923c',
          opacity: 0.5,
          width: 1.5
        },
        move: {
          enable: true,
          speed: 2.5,
          direction: 'none',
          random: false,
          straight: false,
          out_mode: 'out',
          bounce: false,
          attract: {
            enable: false,
            rotateX: 600,
            rotateY: 1200
          }
        }
      },
      interactivity: {
        detect_on: 'canvas',
        events: {
          onhover: {
            enable: true,
            mode: 'grab'
          },
          onclick: {
            enable: true,
            mode: 'push'
          },
          resize: true
        },
        modes: {
          grab: {
            distance: 140,
            line_linked: {
              opacity: 0.7
            }
          },
          push: {
            particles_nb: 4
          }
        }
      },
      retina_detect: true
    });

    // Image rotation for technology sections
    const aiImages = [
      'https://images.unsplash.com/photo-1677442136019-21780ecad995?w=800&h=600&fit=crop',
      'https://images.unsplash.com/photo-1555255707-c07966088b7b?w=800&h=600&fit=crop',
      'https://images.unsplash.com/photo-1620712943543-bcc4688e7485?w=800&h=600&fit=crop'
    ];

    const iotImages = [
      'https://images.unsplash.com/photo-1561557944-6e7860d1a7eb?w=800&h=600&fit=crop',
      'https://images.unsplash.com/photo-1518770660439-4636190af475?w=800&h=600&fit=crop',
      'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=800&h=600&fit=crop'
    ];

    const cyberImages = [
      'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?w=800&h=600&fit=crop',
      'https://images.unsplash.com/photo-1563986768609-322da13575f3?w=800&h=600&fit=crop',
      'https://images.unsplash.com/photo-1614064641938-3bbee52942c7?w=800&h=600&fit=crop'
    ];

    let aiIndex = 0;
    let iotIndex = 0;
    let cyberIndex = 0;

    function rotateImages() {
      const aiImg = document.getElementById('aiImage');
      const iotImg = document.getElementById('iotImage');
      const cyberImg = document.getElementById('cyberImage');

      if (aiImg) {
        aiIndex = (aiIndex + 1) % aiImages.length;
        aiImg.style.opacity = '0';
        setTimeout(() => {
          aiImg.src = aiImages[aiIndex];
          aiImg.style.opacity = '1';
        }, 300);
      }

      if (iotImg) {
        iotIndex = (iotIndex + 1) % iotImages.length;
        iotImg.style.opacity = '0';
        setTimeout(() => {
          iotImg.src = iotImages[iotIndex];
          iotImg.style.opacity = '1';
        }, 300);
      }

      if (cyberImg) {
        cyberIndex = (cyberIndex + 1) % cyberImages.length;
        cyberImg.style.opacity = '0';
        setTimeout(() => {
          cyberImg.src = cyberImages[cyberIndex];
          cyberImg.style.opacity = '1';
        }, 300);
      }
    }

    // Rotate images every 4 seconds
    setInterval(rotateImages, 4000);

    // Typing animation
    const text = "EmergingTechnologies";
    const typingEl = document.getElementById("typing");
    let index = 0;
    let forward = true;

    function type() {
      if (forward) {
        index++;
        if (index === text.length) {
          setTimeout(() => { forward = false; }, 2000);
        }
      } else {
        index--;
        if (index === 0) {
          setTimeout(() => { forward = true; }, 500);
        }
      }
      typingEl.textContent = text.slice(0, index);
      setTimeout(type, forward ? 150 : 100);
    }

    type();

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

    // Close mobile menu when clicking a link
    const mobileLinks = mobileMenu.querySelectorAll('a');
    mobileLinks.forEach(link => {
      link.addEventListener('click', () => {
        mobileMenu.classList.remove('active');
        mobileOverlay.classList.remove('active');
      });
    });

    // Scroll reveal animation
    const reveals = document.querySelectorAll('.reveal');
    
    function checkReveal() {
      reveals.forEach(reveal => {
        const windowHeight = window.innerHeight;
        const elementTop = reveal.getBoundingClientRect().top;
        const elementVisible = 150;
        
        if (elementTop < windowHeight - elementVisible) {
          reveal.classList.add('active');
        }
      });
    }

    window.addEventListener('scroll', checkReveal);
    checkReveal(); // Check on load

    // Back to top button
    const backToTop = document.getElementById('backToTop');
    
    window.addEventListener('scroll', () => {
      if (window.pageYOffset > 300) {
        backToTop.style.opacity = '1';
        backToTop.style.pointerEvents = 'auto';
      } else {
        backToTop.style.opacity = '0';
        backToTop.style.pointerEvents = 'none';
      }
    });

    backToTop.addEventListener('click', () => {
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          const offset = 80; // Height of fixed navbar
          const targetPosition = target.offsetTop - offset;
          window.scrollTo({
            top: targetPosition,
            behavior: 'smooth'
          });
        }
      });
    });
      // Loader Script
 // Enhanced Loader Script with Percentage Counter
window.addEventListener('load', function() {
  const preloader = document.getElementById('preloader');
  const percentageEl = document.getElementById('loadingPercentage');
  
  let percentage = 0;
  const interval = setInterval(() => {
    percentage += Math.random() * 15;
    if (percentage > 100) percentage = 100;
    percentageEl.textContent = Math.floor(percentage) + '%';
    
    if (percentage >= 100) {
      clearInterval(interval);
      
      setTimeout(() => {
        preloader.classList.add('preloader-hidden');
        
        setTimeout(() => {
          preloader.remove();
        }, 600);
      }, 500);
    }
  }, 150);
});
 // Counter Animation on Hover - Continuous Counting
  document.querySelectorAll('.stat-card').forEach(card => {
    const numberEl = card.querySelector('.stat-number');
    const target = parseInt(numberEl.getAttribute('data-target'));
    const suffix = numberEl.getAttribute('data-suffix');
    let animationInterval = null;

    card.addEventListener('mouseenter', () => {
      if (animationInterval) return; // Already animating

      let current = 0;
      const increment = target / 60; // 60 steps for smooth animation
      
      animationInterval = setInterval(() => {
        current += increment;
        if (current >= target) {
          current = 0; // Reset to 0 and continue counting
        }
        numberEl.textContent = Math.floor(current) + suffix;
      }, 25); // Update every 25ms
    });

    card.addEventListener('mouseleave', () => {
      if (animationInterval) {
        clearInterval(animationInterval);
        animationInterval = null;
        numberEl.textContent = target + suffix; // Return to original value
      }
    });
  });
  </script>
</body>

</html>