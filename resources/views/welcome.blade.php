<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('images/logo1.png') }}" type="image/png">
    <title>Welcome to GeoLokasi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-[#1742b4]/10 to-white min-h-screen overflow-x-hidden"
      x-data="{ sidebarOpen: false }">

<!-- Sidebar Overlay -->
<div x-show="sidebarOpen"
{{--     x-transition:enter="transition-opacity ease-linear duration-300"--}}
{{--     x-transition:enter-start="opacity-0"--}}
{{--     x-transition:enter-end="opacity-100"--}}
{{--     x-transition:leave="transition-opacity ease-linear duration-300"--}}
{{--     x-transition:leave-start="opacity-100"--}}
{{--     x-transition:leave-end="opacity-0"--}}
{{--     class="fixed inset-0 bg-gray-600 bg-opacity-75 z-20"--}}
     @click="sidebarOpen = false"></div>

<!-- Sidebar -->
<aside x-show="sidebarOpen"
{{--       x-transition:enter="transform transition-transform duration-200"--}}
{{--       x-transition:enter-start="-translate-x-full"--}}
{{--       x-transition:enter-end="translate-x-0"--}}
{{--       x-transition:leave="transform transition-transform duration-200"--}}
{{--       x-transition:leave-start="translate-x-0"--}}
{{--       x-transition:leave-end="-translate-x-full"--}}
       class="fixed top-7 left-0 z-40 w-64 h-screen pt-16 bg-[#1742b4] transition-transform">
    <div class="h-full px-3 py-4 overflow-y-auto flex flex-col justify-between">
        <!-- Navigation Menu -->
        <ul class="space-y-2">
            <li>
                <a href="{{ route('dashboard') }}"
                   class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span class="ml-3">Dashboard</span>
                </a>
            </li>
            @auth
                <li>
                    <a href="{{ route('leaderboard') }}"
                       class="sidebar-link {{ request()->routeIs('leaderboard') ? 'active' : '' }}">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <span class="ml-3">Leaderboard</span>
                    </a>
                </li>
            @endauth
        </ul>

        <!-- Logout Button -->
        <div class="pt-2 pb-6 border-t border-[#1742b4]/30">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-button">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span class="ml-3">Logout</span>
                </button>
            </form>
        </div>
    </div>
</aside>

<!-- Navigation -->
<nav class="bg-white/80 backdrop-blur-lg fixed w-full z-50 shadow-sm">
    <div class="px-3 py-3 lg:px-5 lg:pl-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg hover:bg-gray-100">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <a href="{{ route('welcome') }}" class="flex items-center ml-3">
                    <img src="{{ asset('images/logo.png') }}" alt="GeoLokasi Logo" class="h-16">
                </a>
            </div>

            <!-- Auth section -->
            <div class="flex items-center space-x-4">
                @auth
                    <div class="flex items-center">
                        <span class="text-gray-600">Welcome, </span>
                        <a href="{{ route('dashboard') }}" class="font-semibold text-[#1742b4] ml-1 hover:text-[#1742b4]/80 transition-colors duration-150">
                            {{ Auth::user()->name }}
                        </a>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit"
                                class="group px-4 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-[#1742b4] to-[#1742b4]/80 rounded-lg hover:from-[#1742b4]/80 hover:to-[#1742b4] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1742b4] transition-all duration-300">
                                <span class="flex items-center space-x-2">
                                    <span>Logout</span>
                                    <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                    </svg>
                                </span>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                       class="text-gray-600 hover:text-[#1742b4] transition-colors duration-150 hover:scale-105 transform">
                        Login
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<!-- Hero Section with enhanced animations -->
<div class="relative pt-20">
    <div class="absolute inset-0 z-0 overflow-hidden">
        <div class="absolute -top-4 -left-4 w-72 h-72 bg-gradient-to-br from-[#1742b4]/20 to-[#1742b4]/30 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
        <div class="absolute top-1/2 right-1/4 w-72 h-72 bg-gradient-to-br from-[#1742b4]/30 to-[#1742b4]/40 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000"></div>
        <div class="absolute bottom-1/4 left-1/3 w-72 h-72 bg-gradient-to-br from-[#1742b4]/20 to-[#1742b4]/30 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-16">
        <div class="text-center">
            <h1 class="text-5xl font-extrabold text-gray-900 sm:text-6xl md:text-7xl animate-fade-in">
                Welcome to <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#1742b4] to-[#1742b4]/70">GeoLokasi</span>
            </h1>
            <p class="mt-6 text-xl text-gray-600 max-w-3xl mx-auto animate-slide-up">
                Discover and explore locations with our advanced geolocation services. Track, manage, and analyze spatial data with ease.
            </p>
            <div class="mt-10 animate-bounce-in">
                @auth
                    <a href="{{ route('dashboard') }}"
                       class="group inline-flex items-center px-8 py-3 text-lg font-medium text-white bg-gradient-to-r from-[#1742b4] to-[#1742b4]/80 rounded-lg hover:from-[#1742b4]/80 hover:to-[#1742b4] transition-all duration-300 transform hover:scale-105">
                        <span>Go to Dashboard</span>
                        <svg class="ml-2 w-5 h-5 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="group inline-flex items-center px-8 py-3 text-lg font-medium text-white bg-gradient-to-r from-[#1742b4] to-[#1742b4]/80 rounded-lg hover:from-[#1742b4]/80 hover:to-[#1742b4] transition-all duration-300 transform hover:scale-105">
                        <span>Get Started</span>
                        <svg class="ml-2 w-5 h-5 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                @endauth
            </div>
            <div class="mt-6 flex justify-center space-x-8">
                <a href="https://www.mara.gov.my/"
                   target="_blank"
                   class="transform transition-all duration-300 hover:scale-110 relative group">
                    <div class="absolute -inset-2 bg-gradient-to-r from-[#1742b4] to-[#1742b4]/70 rounded-lg blur opacity-0 group-hover:opacity-75 transition duration-300"></div>
                    <img src="{{ asset('images/mara.png') }}"
                         alt="MARA Logo"
                         class="h-24 relative animate-fade-in hover:shadow-xl rounded-lg transition-shadow duration-300">
                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <span class="text-white text-sm font-semibold bg-black/50 px-3 py-1 rounded-full">
                                Visit MARA
                            </span>
                    </div>
                </a>

                <a href="http://www.kpmim.edu.my/"
                   target="_blank"
                   class="transform transition-all duration-300 hover:scale-110 relative group">
                    <div class="absolute -inset-2 bg-gradient-to-r from-[#1742b4] to-[#1742b4]/70 rounded-lg blur opacity-0 group-hover:opacity-75 transition duration-300"></div>
                    <img src="{{ asset('images/kpm.png') }}"
                         alt="KPMIM Logo"
                         class="h-24 relative animate-fade-in hover:shadow-xl rounded-lg transition-shadow duration-300">
                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <span class="text-white text-sm font-semibold bg-black/50 px-3 py-1 rounded-full">
                                Visit KPMIM
                            </span>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Features & Campus Section -->
<div class="relative bg-white/80 backdrop-blur-lg py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- GeoLokasi Features -->
        <div class="text-center animate-fade-in mb-16">
            <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-[#1742b4] to-[#1742b4]/70 sm:text-4xl leading-[3.0rem]">
                GeoLokasi Features
            </h2>
            <p class="mt-4 text-lg text-gray-600">
                Advanced location tracking and attendance management for KPMIM
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-20">
            <!-- Feature Cards -->
            <div class="program-card bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden">
                <div class="h-48 overflow-hidden bg-gradient-to-br from-[#1742b4]/10 to-[#1742b4]/5 flex items-center justify-center">
                    <svg class="w-24 h-24 text-[#1742b4]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    </svg>
                </div>
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Real-time Location</h3>
                    <p class="text-gray-600 text-sm">Track attendance with precise GPS location verification</p>
                </div>
            </div>

            <div class="program-card bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden">
                <div class="h-48 overflow-hidden bg-gradient-to-br from-[#1742b4]/10 to-[#1742b4]/5 flex items-center justify-center">
                    <svg class="w-24 h-24 text-[#1742b4]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Attendance Records</h3>
                    <p class="text-gray-600 text-sm">Automated attendance tracking for KPMIM students</p>
                </div>
            </div>

            <div class="program-card bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden">
                <div class="h-48 overflow-hidden bg-gradient-to-br from-[#1742b4]/10 to-[#1742b4]/5 flex items-center justify-center">
                    <svg class="w-24 h-24 text-[#1742b4]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Analytics Dashboard</h3>
                    <p class="text-gray-600 text-sm">Comprehensive attendance analytics and reporting</p>
                </div>
            </div>

            <div class="program-card bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden">
                <div class="h-48 overflow-hidden bg-gradient-to-br from-[#1742b4]/10 to-[#1742b4]/5 flex items-center justify-center">
                    <svg class="w-24 h-24 text-[#1742b4]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">User Management</h3>
                    <p class="text-gray-600 text-sm">Easy management of students and staff accounts</p>
                </div>
            </div>
        </div>

        <!-- Enhanced Footer with Contact Information -->
        <footer class="mt-16 bg-gradient-to-b from-white to-[#1742b4]/5">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 py-12">
                    <!-- College Info -->
                    <div class="space-y-4">
                        <div class="flex items-center space-x-2">
                            <img src="{{ asset('images/kpm.png') }}" alt="KPMIM Logo" class="h-10">
                            <h3 class="text-lg font-bold text-gray-900">KPMIM</h3>
                        </div>
                        <p class="text-gray-600 text-sm">
                            Kolej Profesional MARA Indera Mahkota - Transforming Education, Empowering Future Leaders
                        </p>
                        <div class="flex space-x-4 pt-2">
                            <a href="https://www.facebook.com/mediakpmim?locale=ms_MY" class="text-[#1742b4] hover:text-[#1742b4]/80 transition-colors">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/>
                                </svg>
                            </a>

                            <a href="https://www.instagram.com/teamkpmim/" class="text-[#1742b4] hover:text-[#1742b4]/80 transition-colors">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Contact Info -->
                    <div class="space-y-4">
                        <h4 class="text-lg font-semibold text-gray-900">Contact Us</h4>
                        <div class="space-y-3">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-[#1742b4]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                <span class="text-gray-600">09-5736304 / 6346</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-[#1742b4]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-gray-600">info@kpmim.edu.my</span>
                            </div>
                            <div class="flex items-start space-x-3">
                                <svg class="w-5 h-5 text-[#1742b4] mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                </svg>
                                <span class="text-gray-600">KOLEJ PROFESIONAL MARA INDERA MAHKOTA
                                        Lot 14687, Bandar Indera Mahkota
                                        Jln Sungai Lembing 25200 Kuantan Pahang</span>
                            </div>
                        </div>
                    </div>

                    <!-- Operating Hours -->
                    <div class="space-y-4">
                        <h4 class="text-lg font-semibold text-gray-900">Operating Hours</h4>
                        <div class="space-y-2">
                            <div class="text-gray-600">
                                <span class="font-medium">Monday - Thursday</span><br>
                                8:00 AM - 5:00 PM
                            </div>
                            <div class="text-gray-600">
                                <span class="font-medium">Friday</span><br>
                                8:00 AM - 12:15 PM<br>
                                2:45 PM - 5:00 PM
                            </div>
                            <div class="text-gray-600">
                                <span class="font-medium">Weekend</span><br>
                                Closed
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Copyright -->
                <div class="border-t border-gray-200 py-6">
                    <p class="text-center text-sm text-gray-600">
                        © {{ date('Y') }} KPMIM. All rights reserved. Powered by GeoLokasi
                    </p>
                </div>
            </div>
        </footer>
    </div>
</div>
</body>
</html>
