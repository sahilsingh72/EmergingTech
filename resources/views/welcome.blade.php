<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>EmergingTech</title>
    <!-- ✅ Add favicon -->
    <link rel="icon" type="image/png" href="{{ asset('Et.webp') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Styles / Scripts -->
    <style>
        .hero {
            text-align: center;
            padding: 80px 20px;
        }

        .hero h2 {
            font-size: 40px;
            color: #ff6f00;
        }

        .hero p {
            margin-top: 15px;
            font-size: 18px;
            color: #444;
        }

        .technologies {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            padding: 50px;
        }

        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-8px);
        }

        .card img {
            width: 80px;
            height: 80px;
            object-fit: contain;
            margin-bottom: 15px;
        }

        .card h3 {
            color: #ff6f00;
            margin-bottom: 10px;
        }

        .card p {
            color: #555;
            font-size: 14px;
        }
    </style>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
</head>

<body class="flex flex-col min-h-screen" style="background-color:#ffedd2">
    {{-- <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex flex-col min-h-screen"> --}}

    <!-- ✅ Header -->
    <header class="w-full max-w-6xl mx-auto flex justify-between items-center"
        style="border:1px solid #898888; padding:0 2%; background: linear-gradient(to right, #EF6C00, #FFB74D)">
        <img src="{{ asset('Et.webp') }}" alt="Logo" style="width:8%">
        <h1 class="text-xl font-semibold" style="text-align:left">Statewide Training & Awareness Camps on Emerging
            Technologies</h1>

        @if (Route::has('login'))
            <nav class="flex items-center gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}"
                        class="px-5 py-2 rounded-md border dark:border-[#3E3E3A] text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-black hover:text-white transition">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="px-4 py-1 rounded-md border dark:border-[#3E3E3A] text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]  hover:text-white transition"
                        style="background-color:transparent;" onmouseover="this.style.backgroundColor='#FF9800'"
                        onmouseout="this.style.backgroundColor='transparent'">
                        Log in
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
    </header>

    <!-- ✅ Hero Section -->
    <main class="flex-1 flex flex-col items-center justify-center text-center px-6">
        <section class="hero">
            <h2>Discover Emerging Technologies</h2>
            <p>Exploring innovations shaping our future: AI, IoT & Robotics and Cybersecurity.</p>
        </section>

        <section class="technologies">
            <div class="card">
                <img src="https://img.icons8.com/fluency/96/artificial-intelligence.png" alt="AI">
                <h3>AI Discovery</h3>
                <p>Learn the foundations of Artificial Intelligence and how it is transforming industries worldwide.</p>
            </div>
            <div class="card">
                <img src="https://img.icons8.com/fluency/96/internet-of-things.png" alt="IoT">
                <h3>IoT & Robotics Innovation</h3>
                <p>Explore how IoT and robotics are driving automation and smart innovations for the future.</p>
            </div>
            <div class="card">
                <img src="https://img.icons8.com/fluency/96/cyber-security.png" alt="Cybersecurity">
                <h3>Cybersecurity Awareness</h3>
                <p>Understand the importance of digital safety and how to protect data from cyber threats.</p>
            </div>
        </section>


        {{-- @if (Route::has('register'))
                <a href="{{ route('register') }}"
                   class="px-8 py-3 rounded-full bg-black text-white text-lg font-semibold hover:bg-[#f53003] transition">
                   🚀 Register Now
                </a>
            @endif --}}
    </main>

    <!-- ✅ Footer -->
    <!-- ✅ Footer -->
    <footer class="w-full py-6 bg-transparent">
        <div class="flex justify-center">
            <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                © {{ date('Y') }} EmergingTech. All rights reserved.
            </p>
        </div>
    </footer>

</body>

</html>
