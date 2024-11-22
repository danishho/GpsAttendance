<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="refresh" content="30">
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

        <!-- Main Content -->
        <div class="p-4 sm:ml-64 pt-20">
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
                                        <div class="flex items-center space-x-4">
                                            <div class="flex-shrink-0">
                                                <img class="h-8 w-8 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}" alt="{{ $user->name }}">
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate">{{ $user->name }}</p>
                                                <p class="text-sm text-gray-500 truncate">{{ $user->email }}</p>
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
                                        <div class="flex items-center space-x-4">
                                            <div class="flex-shrink-0">
                                                <img class="h-8 w-8 rounded-full" 
                                                    src="{{ $record->device->user->profile_photo_url }}" 
                                                    alt="{{ $record->device->user->name }}">
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate">
                                                    {{ $record->device->user->name }}
                                                </p>
                                                <p class="text-sm text-gray-500 truncate">
                                                    {{ $record->date }} at {{ $record->check_in }}
                                                </p>
                                            </div>
                                            <div>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $record->status === 'On Time' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                    {{ $record->status }}
                                                </span>
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
