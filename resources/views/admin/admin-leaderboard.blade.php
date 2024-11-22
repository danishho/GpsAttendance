<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Leaderboard - GeoLokasi</title>
    @vite('resources/css/app.css')
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50">
    <div x-data="{ 
        isOpen: localStorage.getItem('sidebarOpen') === 'true',
        toggleSidebar() {
            this.isOpen = !this.isOpen;
            localStorage.setItem('sidebarOpen', this.isOpen);
        }
    }">
        @include('layouts.admin-nav')

        <!-- Main Content -->
        <div class="p-4 sm:ml-64 pt-20">
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
                                    <tr class="hover:bg-gray-50">
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
                                                        ($entry->average_points >= 5 ? 'bg-yellow-500' : 
                                                        'bg-red-500')) 
                                                    }}"
                                                    style="width: {{ min(($entry->average_points/10) * 100, 100) }}%;">
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
</body>
</html> 