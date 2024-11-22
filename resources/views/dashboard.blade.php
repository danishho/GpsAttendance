<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - GeoLokasi</title>
    @vite('resources/css/app.css')
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50">
<div x-data="{
        isOpen: localStorage.getItem('sidebarOpen') === 'true',
        toggleSidebar() {
            this.isOpen = !this.isOpen;
            localStorage.setItem('sidebarOpen', this.isOpen);
        }
    }">
    <!-- Top Navigation Bar -->
    <nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200">
        <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <button @click="toggleSidebar()" class="p-2 rounded-lg hover:bg-gray-100">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <a href="{{ route('welcome') }}" class="flex items-center ml-3">
                        <img src="{{ asset('images/logo.png') }}" alt="GeoLokasi Logo" class="h-16">

                    </a>
                </div>
                <div class="flex items-center">
                    <span class="mr-4 text-sm text-gray-600">Welcome, {{ Auth::user()->name }}</span>
                    <img class="w-8 h-8 rounded-full" src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}" alt="user photo">
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <aside x-show="isOpen"
           x-transition:enter="transform transition-transform duration-200"
           x-transition:enter-start="-translate-x-full"
           x-transition:enter-end="translate-x-0"
           x-transition:leave="transform transition-transform duration-200"
           x-transition:leave-start="translate-x-0"
           x-transition:leave-end="-translate-x-full"
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
                <li>
                    <a href="{{ route('leaderboard') }}"
                       class="sidebar-link {{ request()->routeIs('leaderboard') ? 'active' : '' }}">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <span class="ml-3">Leaderboard</span>
                    </a>
                </li>
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

    <!-- Main Content -->
    <div class="transition-all duration-200"
         :class="{ 'ml-64': isOpen }">
        <div class="p-4 mt-14">
            <div class="p-4 rounded-lg mt-8">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 gap-6 mb-6 sm:grid-cols-2 lg:grid-cols-4">
                    <!-- Check-ins Card -->
                    <div class="p-4 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Total Check-ins</p>
                                <p class="text-2xl font-bold text-[#1742b4]">{{ $totalCheckins }}</p>
                            </div>
                            <div class="p-3 bg-[#1742b4]/10 rounded-full">
                                <svg class="w-6 h-6 text-[#1742b4]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Check-outs Card -->
                    <div class="p-4 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Total Check-outs</p>
                                <p class="text-2xl font-bold text-[#1742b4]">{{ $totalCheckouts }}</p>
                            </div>
                            <div class="p-3 bg-[#1742b4]/10 rounded-full">
                                <svg class="w-6 h-6 text-[#1742b4]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Monthly Attendance Card -->
                    <div class="p-4 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Monthly Attendance</p>
                                <p class="text-2xl font-bold text-[#1742b4]">{{ $monthlyAttendance }}</p>
                            </div>
                            <div class="p-3 bg-[#1742b4]/10 rounded-full">
                                <svg class="w-6 h-6 text-[#1742b4]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Average Points Card -->
                    <div class="p-4 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Average Points</p>
                                <p class="text-2xl font-bold text-[#1742b4]">{{ number_format($averagePoints, 1) }}</p>
                            </div>
                            <div class="p-3 bg-[#1742b4]/10 rounded-full">
                                <svg class="w-6 h-6 text-[#1742b4]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status Indicator -->
                <div class="p-4 mb-6 bg-white rounded-lg shadow-sm">
                    <h2 class="mb-2 text-lg font-semibold text-gray-900">Current Status</h2>
                    <div class="flex items-center">
                            <span class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full
                                @if(isset($currentStatus) && $currentStatus === 'Inside Geofence')
                                    text-green-800 bg-green-100
                                @else
                                    text-red-800 bg-red-100
                                @endif">
                                <span class="w-2 h-2 mr-1 rounded-full
                                    @if(isset($currentStatus) && $currentStatus === 'Inside Geofence')
                                        bg-green-600
                                    @else
                                        bg-red-600
                                    @endif">
                                </span>
                                {{ $currentStatus ?? 'Outside Geofence' }}
                            </span>
                    </div>
                    <p class="mt-2 text-sm text-gray-600">Attendance is automatically recorded based on your location</p>
                </div>

                <!-- Attendance Table -->
                <div class="p-4 mb-4 bg-white rounded-lg shadow-sm">
                    <h2 class="mb-4 text-xl font-semibold text-gray-900">Attendance History</h2>
                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">Date</th>
                                <th scope="col" class="px-6 py-3">Check In</th>
                                <th scope="col" class="px-6 py-3">Check Out</th>
                                <th scope="col" class="px-6 py-3">Status Check-In</th>
                                <th scope="col" class="px-6 py-3">Status Check-Out</th>
                                <th scope="col" class="px-6 py-3">Total Hours</th>
                                <th scope="col" class="px-6 py-3">Points</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($attendanceRecords as $record)
                                <tr class="bg-white border-b">
                                    <td class="px-6 py-4">{{ $record->date }}</td>
                                    <td class="px-6 py-4">{{ $record->check_in }}</td>
                                    <td class="px-6 py-4">{{ $record->check_out ?? 'Not checked out' }}</td>
                                    <td class="px-6 py-4">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                                 {{ $record->status_checkin === 'On Time' ? 'bg-green-100 text-green-800' :
                                                   ($record->status_checkin === 'Late' ? 'bg-yellow-100 text-yellow-800' :
                                                   'bg-red-100 text-red-800') }}">
                                                {{ $record->status_checkin ? ucfirst($record->status_checkin) : 'Unknown' }}
                                            </span>
                                    </td>
                                    <td class="px-6 py-4">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                                {{ $record->status_checkin === 'Completed' ? 'bg-green-100 text-green-800' :
                                                   ($record->status_checkin === 'Warning: Less than 8 hours' ? 'bg-yellow-100 text-yellow-800' :
                                                   'bg-red-100 text-red-800') }}">
                                                {{ $record->status_checkout ? ucfirst($record->status_checkout) : 'Unknown' }}
                                            </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $hours = floor($record->total_hours);
                                            $minutes = round(($record->total_hours - $hours) * 60);
                                        @endphp
                                        @if($hours == 0 && $minutes == 0)
                                           -
                                        @else
                                            {{ $hours > 0 ? $hours . ' ' . Str::plural('hour', $hours) . ' ' : '' }}
                                            {{ $minutes > 0 ? $minutes . ' ' . Str::plural('minute', $minutes) : '' }}
                                            {{ $hours == 0 && $minutes == 0 ? '0 minutes' : '' }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">{{ $record->points_earned }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Performance Card -->
                <div class="p-4 bg-white rounded-lg shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-semibold text-gray-900">Performance Ranking</h2>
                        <div class="flex items-center">
                            <span class="text-2xl font-bold text-[#1742b4]">{{ number_format($averagePoints, 1) }}</span>
                            <span class="ml-1 text-sm text-gray-600">points</span>
                        </div>
                    </div>
                    <div class="w-full h-2 bg-gray-200 rounded-full">
                        <div class="h-2 bg-[#1742b4] rounded-full" style="width: {{ min(($averagePoints/10) * 100, 100) }}%"></div>
                    </div>
                    <p class="mt-2 text-sm text-gray-600">Based on attendance consistency and punctuality</p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
