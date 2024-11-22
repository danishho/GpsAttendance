<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Leaderboard - GeoLokasi</title>
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
        <div class="transition-all duration-200" :class="{ 'ml-64': isOpen }">
            <div class="p-4 mt-14">
                <div class="p-4 rounded-lg mt-8">
                    <!-- Top 3 Cards -->
                    <div class="grid grid-cols-1 gap-6 mb-6 sm:grid-cols-3">
                        @foreach($leaderboard->take(3) as $index => $top)
                            <div class="p-6 bg-white rounded-lg shadow-sm hover:shadow-md transition-all duration-300">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            <div class="relative">
                                                <img class="w-16 h-16 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($top->name) }}&size=64" alt="">
                                                <span class="absolute -top-2 -right-2 flex items-center justify-center w-8 h-8 rounded-full
                                                    {{ $index === 0 ? 'bg-yellow-100 text-yellow-800' :
                                                       ($index === 1 ? 'bg-gray-100 text-gray-800' :
                                                       'bg-orange-100 text-orange-800') }}
                                                    text-lg font-bold">
                                                    {{ $index + 1 }}
                                                </span>
                                            </div>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $top->name }}</h3>
                                            <p class="text-sm text-gray-600">{{ $top->attendance_days }} days attended</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-2xl font-bold text-[#1742b4]">{{ number_format($top->average_points, 1) }}</span>
                                        <p class="text-sm text-gray-600">points</p>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{
                                        $top->average_points >= 9 ? 'bg-green-100 text-green-800' :
                                        ($top->average_points >= 7 ? 'bg-blue-100 text-blue-800' :
                                        ($top->average_points >= 5 ? 'bg-yellow-100 text-yellow-800' :
                                        'bg-red-100 text-red-800'))
                                    }}">
                                        {{
                                            $top->average_points >= 9 ? 'Excellent' :
                                            ($top->average_points >= 7 ? 'Good' :
                                            ($top->average_points >= 5 ? 'Average' :
                                            'Needs Improvement'))
                                        }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Points System Information Card -->
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
                        <div class="p-6 border-b border-gray-200">
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5 text-[#1742b4]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <h2 class="text-xl font-semibold text-gray-900">Points System</h2>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Attendance Points -->
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900 uppercase mb-3">Attendance Points</h3>
                                    <ul class="space-y-2">
                                        <li class="flex items-center text-sm">
                                            <span class="w-24">On Time:</span>
                                            <span class="font-semibold text-[#1742b4]">10 points</span>
                                        </li>
                                        <li class="flex items-center text-sm">
                                            <span class="w-24">Late:</span>
                                            <span class="font-semibold text-[#1742b4]">5 points</span>
                                        </li>
                                    </ul>
                                </div>

                                <!-- Performance Levels -->
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900 uppercase mb-3">Performance Levels</h3>
                                    <ul class="space-y-2">
                                        <li class="flex items-center text-sm">
                                            <span class="w-24">≥ 9 points:</span>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Excellent
                                            </span>
                                        </li>
                                        <li class="flex items-center text-sm">
                                            <span class="w-24">≥ 7 points:</span>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                Good
                                            </span>
                                        </li>
                                        <li class="flex items-center text-sm">
                                            <span class="w-24">≥ 5 points:</span>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Average
                                            </span>
                                        </li>
                                        <li class="flex items-center text-sm">
                                            <span class="w-24">< 5 points:</span>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Needs Improvement
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="mt-4 text-sm text-gray-600">
                                <p>Average points are calculated based on your attendance status for each day. The more consistent you are with on-time attendance, the higher your average points will be.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Leaderboard Table -->
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="p-6 border-b border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-900">Complete Rankings</h2>
                            <p class="mt-1 text-sm text-gray-600">Based on attendance performance and consistency</p>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rank</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Days Present</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Average Points</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Performance</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($leaderboard as $entry)
                                        <tr class="hover:bg-gray-50 {{ !auth()->user()->is_admin && $entry->id === auth()->id() ? 'bg-blue-50' : '' }}">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full
                                                    {{ $entry->rank <= 3 ?
                                                        ($entry->rank === 1 ? 'bg-yellow-100 text-yellow-800' :
                                                        ($entry->rank === 2 ? 'bg-gray-100 text-gray-800' :
                                                        'bg-orange-100 text-orange-800')) :
                                                        'text-gray-500' }}">
                                                    {{ $entry->rank }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <img class="w-8 h-8 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($entry->name) }}" alt="">
                                                    <span class="ml-2 text-sm font-medium text-gray-900">{{ $entry->name }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $entry->attendance_days }} days
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="text-[#1742b4] font-semibold">{{ number_format($entry->average_points, 1) }}</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="w-32 h-2 bg-gray-200 rounded-full mr-2">
                                                        <div class="h-2 rounded-full {{
                                                            $entry->average_points >= 9 ? 'bg-green-500' :
                                                            ($entry->average_points >= 7 ? 'bg-blue-500' :
                                                            ($entry->average_points >= 5 ? 'bg-yellow-500' : 'bg-red-500'))
                                                        }}"
                                                        style="width: {{ min(($entry->average_points/10) * 100, 100) }}%">
                                                        </div>
                                                    </div>
                                                    <span class="text-sm font-medium {{
                                                        $entry->average_points >= 9 ? 'text-green-700' :
                                                        ($entry->average_points >= 7 ? 'text-blue-700' :
                                                        ($entry->average_points >= 5 ? 'text-yellow-700' :
                                                        'text-red-700'))
                                                    }}">
                                                        {{
                                                            $entry->average_points >= 9 ? 'Excellent' :
                                                            ($entry->average_points >= 7 ? 'Good' :
                                                            ($entry->average_points >= 5 ? 'Average' :
                                                            'Needs Improvement'))
                                                        }}
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
