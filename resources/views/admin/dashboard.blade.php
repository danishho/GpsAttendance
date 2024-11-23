<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard - GeoLokasi</title>
    @vite('resources/css/app.css')
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-50">
    <div x-data="{
        isOpen: localStorage.getItem('sidebarOpen') === 'true',
        toggleSidebar() {
            this.isOpen = !this.isOpen;
            localStorage.setItem('sidebarOpen', this.isOpen);
        },
        ...dashboard()
    }" x-init="setInterval(() => refreshData(), 30000)">
        @include('layouts.admin-nav')

        <!-- Move the Dashboard Metrics inside the main content div -->
        <div class="p-4 sm:ml-64 pt-20 mt-10">
            <!-- Welcome Section -->
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Dashboard</h1>
                <p class="text-gray-600">Welcome to GeoLokasi</p>
            </div>

            <!-- Dashboard Metrics -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <!-- Total Employees Card -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Employees</p>
                            <p class="text-2xl font-bold text-[#1742b4]">{{ $totalUsers }}</p>
                        </div>
                        <div class="p-3 bg-[#1742b4]/10 rounded-full">
                            <svg class="w-6 h-6 text-[#1742b4]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- On-Time Percentage Card -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">On-Time Today</p>
                            <p class="text-2xl font-bold text-[#1742b4]">{{ $onTimePercentage }}%</p>
                        </div>
                        <div class="p-3 bg-[#1742b4]/10 rounded-full">
                            <svg class="w-6 h-6 text-[#1742b4]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- On-Time Count Card -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">On-Time Count</p>
                            <p class="text-2xl font-bold text-[#1742b4]">{{ $onTimeCount }}</p>
                        </div>
                        <div class="p-3 bg-[#1742b4]/10 rounded-full">
                            <svg class="w-6 h-6 text-[#1742b4]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Late Count Card -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Late Today</p>
                            <p class="text-2xl font-bold text-[#1742b4]">{{ $lateCount }}</p>
                        </div>
                        <div class="p-3 bg-[#1742b4]/10 rounded-full">
                            <svg class="w-6 h-6 text-[#1742b4]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 mt-8">
                <!-- Recent Activity -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-8">
                    <!-- Recent Users -->
                    <div class="bg-white rounded-lg shadow-sm">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Recent Users</h3>
                        </div>
                        <div class="p-6">
                            <div class="flow-root">
                                <ul class="-my-5 divide-y divide-gray-200" x-html="recentUsersHtml || $el.innerHTML">
                                    @foreach($recentUsers as $user)
                                    <li class="py-4">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-4">
                                                <div class="flex-shrink-0">
                                                    <img class="h-8 w-8 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}" alt="{{ $user->name }}">
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $user->name }}</p>
                                                    <p class="text-sm text-gray-500 truncate">{{ $user->email }}</p>
                                                </div>
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $user->position ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Attendance -->
                    <div class="bg-white rounded-lg shadow-sm">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Recent Attendance</h3>
                        </div>
                        <div class="p-6">
                            <div class="flow-root">
                                <ul class="-my-5 divide-y divide-gray-200" x-html="recentAttendanceHtml || $el.innerHTML">
                                    @foreach($recentAttendance as $record)
                                    <li class="py-4">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-4">
                                                <div class="flex-shrink-0">
                                                    <img class="h-8 w-8 rounded-full"
                                                        src="{{ $record->device->user->profile_photo_url }}"
                                                        alt="{{ $record->device->user->name }}">
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-900 truncate">
                                                        {{ $record->device->user->name }}
                                                        <span class="text-sm text-gray-500 italic">- {{ $record->device->user->position ?? 'N/A' }}</span>
                                                    </p>
                                                    <p class="text-sm text-gray-500 truncate">
                                                        {{ $record->date }} at {{ $record->check_in }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="flex flex-col items-end space-y-2">
                                                @if($record->check_in)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $record->status_checkin === 'On Time' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                                    </svg>
                                                    Check-in: {{ $record->status_checkin }}
                                                </span>
                                                @endif
                                                
                                                @if($record->check_out)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $record->status_checkout === 'Completed' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800' }}">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                                    </svg>
                                                    Check-out: {{ $record->status_checkout }}
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function dashboard() {
            return {
                recentUsersHtml: '',
                recentAttendanceHtml: '',

                async refreshData() {
                    try {
                        const dashboardData = await fetch('/admin/dashboard-data').then(res => res.json());
                        this.recentUsersHtml = this.generateRecentUsersHtml(dashboardData.recentUsers);
                        this.recentAttendanceHtml = this.generateRecentAttendanceHtml(dashboardData.recentAttendance);
                    } catch (error) {
                        console.error('Error refreshing data:', error);
                    }
                }
            }
        }
    </script>
</body>
</html>
