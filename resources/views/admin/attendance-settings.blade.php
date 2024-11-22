<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Attendance Settings - GeoLokasi</title>
    @vite('resources/css/app.css')
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&libraries=places&callback=initMap" defer></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        [x-cloak] { display: none !important; }
        .map-container {
            position: relative;
            border-radius: 0.5rem;
            overflow: hidden;
        }
        .radius-circle {
            position: absolute;
            border: 2px solid #1742b4;
            border-radius: 50%;
            opacity: 0.3;
            background-color: #1742b4;
            pointer-events: none;
        }
    </style>
</head>
<body class="bg-gray-50">
<!-- Toast Container -->
<div aria-live="assertive" class="fixed left-0 right-0 flex items-end px-4 pointer-events-none sm:p-6 sm:items-start z-50" style="top: 5rem;">
    <div class="w-full flex flex-col items-center space-y-4 sm:items-end">
        <!-- Success Toast -->
        @if(session('success'))
            <div x-data="{ show: true }"
                 x-show="show"
                 x-transition:enter="transform ease-out duration-300 transition"
                 x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                 x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 x-init="setTimeout(() => show = false, 3000)"
                 class="max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden sm:mr-6">
                <div class="p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-3 w-0 flex-1 pt-0.5">
                            <p class="text-sm font-medium text-gray-900">Success!</p>
                            <p class="mt-1 text-sm text-gray-500">{{ session('success') }}</p>
                        </div>
                        <div class="ml-4 flex-shrink-0 flex">
                            <button @click="show = false" class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <span class="sr-only">Close</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Error Toast -->
        @if($errors->any())
            <div x-data="{ show: true }"
                 x-show="show"
                 x-transition:enter="transform ease-out duration-300 transition"
                 x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                 x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 x-init="setTimeout(() => show = false, 5000)"
                 class="max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden sm:mr-6">
                <div class="p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-3 w-0 flex-1 pt-0.5">
                            <p class="text-sm font-medium text-gray-900">Error!</p>
                            <ul class="mt-1 text-sm text-gray-500">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="ml-4 flex-shrink-0 flex">
                            <button @click="show = false" class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <span class="sr-only">Close</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<div x-data="{ showModal: false }">
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
            <div class="max-w-7xl mx-auto">
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900">Attendance Settings</h2>
                        <p class="mt-1 text-sm text-gray-600">Configure attendance check-in/out locations and times. The geofence radius is fixed at 50 meters.</p>
                    </div>

                    <form action="{{ route('admin.attendance.settings.update') }}" method="POST" class="p-6">
                        @csrf
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Location Selection Mode Toggle -->
                            <div class="lg:col-span-2">
                                <div class="mb-4 flex space-x-4">
                                    <button type="button"
                                            id="check-in-toggle"
                                            onclick="setSelectionMode('check-in')"
                                            class="px-4 py-2 rounded-md text-sm font-medium transition-colors duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <div class="flex items-center space-x-2">
                                            <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                            <span>Set Check-in Location</span>
                                        </div>
                                    </button>
                                    <button type="button"
                                            id="check-out-toggle"
                                            onclick="setSelectionMode('check-out')"
                                            class="px-4 py-2 rounded-md text-sm font-medium transition-colors duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        <div class="flex items-center space-x-2">
                                            <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                            <span>Set Check-out Location</span>
                                        </div>
                                    </button>
                                </div>

                                <!-- Map Container -->
                                <div class="mb-4">
                                    <select id="location-select"
                                            class="w-full px-4 py-2 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Select a location...</option>
                                        <optgroup label="Educational Hubs">
                                            <option value="3.8386,103.2650">KPM Indera Mahkota</option>
                                            <option value="3.5436,103.4289">Universiti Malaysia Pahang</option>
                                            <option value="3.0642,101.5002">UiTM Shah Alam</option>
                                            <option value="5.3564,100.2928">USM Penang</option>
                                            <option value="2.9260,101.7375">UPM Serdang</option>
                                            <option value="3.1209,101.6538">UM Kuala Lumpur</option>
                                            <option value="3.0685,101.6041">UiTM Puncak Alam</option>
                                        </optgroup>
                                        <optgroup label="Major Cities">
                                            <option value="3.1478,101.6953">Kuala Lumpur</option>
                                            <option value="5.4141,100.3288">Penang</option>
                                            <option value="1.4655,103.7578">Johor Bahru</option>
                                            <option value="3.8077,103.3260">Kuantan</option>
                                            <option value="2.1896,102.2501">Melaka</option>
                                            <option value="6.1184,102.2776">Kota Bharu</option>
                                            <option value="4.5975,101.0901">Ipoh</option>
                                            <option value="1.5535,103.6279">Iskandar Puteri</option>
                                        </optgroup>
                                        <optgroup label="Industrial Areas">
                                            <option value="3.0495,101.5380">Shah Alam</option>
                                            <option value="3.0319,101.3792">Klang</option>
                                            <option value="2.9936,101.7892">Kajang</option>
                                            <option value="5.6415,100.4879">Kulim</option>
                                            <option value="3.0878,101.5829">Subang Jaya</option>
                                            <option value="3.1466,101.6958">Ampang</option>
                                            <option value="3.0446,101.7894">Cheras</option>
                                            <option value="3.1571,101.7139">Pandan Indah</option>
                                        </optgroup>
                                        <optgroup label="Business Districts">
                                            <option value="3.1579,101.7116">KLCC</option>
                                            <option value="3.0470,101.5850">i-City Shah Alam</option>
                                            <option value="3.1288,101.6841">KL Sentral</option>
                                            <option value="3.1167,101.6774">Bukit Bintang</option>
                                            <option value="3.1094,101.6591">Bangsar South</option>
                                            <option value="3.0926,101.6537">Mid Valley</option>
                                        </optgroup>
                                    </select>
                                </div>
                                <div class="map-container">
                                    <div id="map" class="w-full h-[400px]"></div>
                                </div>
                                <p class="mt-2 text-sm text-gray-600">Click on the map to set locations. Blue marker for check-in, red marker for check-out.</p>
                            </div>

                            <!-- Check-in Settings -->
                            <div class="space-y-4 bg-gray-50 p-4 rounded-lg">
                                <div class="flex items-center space-x-2">
                                    <div class="w-4 h-4 bg-blue-500 rounded-full"></div>
                                    <h3 class="text-lg font-medium text-gray-900">Check-in Location & Time</h3>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Latitude</label>
                                        <input type="number" step="any" name="check_in_latitude"
                                               id="check_in_latitude"
                                               value="{{ old('check_in_latitude', $settings->check_in_latitude) }}"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Longitude</label>
                                        <input type="number" step="any" name="check_in_longitude"
                                               id="check_in_longitude"
                                               value="{{ old('check_in_longitude', $settings->check_in_longitude) }}"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Check-in Time</label>
                                    <input type="time" 
                                           name="check_in_time"
                                           value="{{ old('check_in_time', $settings->check_in_time ?? '') }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                            </div>

                            <!-- Check-out Settings -->
                            <div class="space-y-4 bg-gray-50 p-4 rounded-lg">
                                <div class="flex items-center space-x-2">
                                    <div class="w-4 h-4 bg-red-500 rounded-full"></div>
                                    <h3 class="text-lg font-medium text-gray-900">Check-out Location & Time</h3>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Latitude</label>
                                        <input type="number" step="any" name="check_out_latitude"
                                               id="check_out_latitude"
                                               value="{{ old('check_out_latitude', $settings->check_out_latitude) }}"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Longitude</label>
                                        <input type="number" step="any" name="check_out_longitude"
                                               id="check_out_longitude"
                                               value="{{ old('check_out_longitude', $settings->check_out_longitude) }}"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Check-out Time</label>
                                    <input type="time" 
                                           name="check_out_time"
                                           value="{{ old('check_out_time', $settings->check_out_time ?? '') }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-between">
                            <span class="text-sm text-gray-600">Geofence radius is fixed at 50 meters</span>
                            <div class="flex space-x-3">
                                <button type="button"
                                        @click="showModal = true"
                                        class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    Reset Settings
                                </button>
                                <button type="submit"
                                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Save Settings
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Reset Confirmation Modal -->
    <div x-cloak
         x-show="showModal"
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-title"
         role="dialog"
         aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                 aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="showModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Reset Settings
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Are you sure you want to reset all attendance settings? This action cannot be undone.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                    <button type="button"
                            @click="showModal = false; resetSettings();"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Reset
                    </button>
                    <button type="button"
                            @click="showModal = false"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let map;
    let checkInMarker;
    let checkOutMarker;
    let checkInCircle;
    let checkOutCircle;
    let selectionMode = 'check-in';
    const defaultCenter = { lat: 4.2105, lng: 101.9758 };
    const defaultZoom = 6;
    const radiusInMeters = 50;
    let searchBox;
    let searchInput;
    const checkInToggle = document.getElementById('check-in-toggle');
    const checkOutToggle = document.getElementById('check-out-toggle');

    function initMap() {
        try {
            map = new google.maps.Map(document.getElementById("map"), {
                center: defaultCenter,
                zoom: defaultZoom,
                styles: [
                    {
                        featureType: "poi",
                        elementType: "labels",
                        stylers: [{ visibility: "on" }]
                    }
                ],
                mapTypeControl: true,
                streetViewControl: true,
                fullscreenControl: true
            });

            // Add location select handler
            const locationSelect = document.getElementById('location-select');
            locationSelect.addEventListener('change', function() {
                if (this.value) {
                    const [lat, lng] = this.value.split(',').map(Number);
                    const position = { lat, lng };

                    // Update map
                    map.setCenter(position);
                    map.setZoom(17); // Closer zoom for better location visibility

                    // Optional: Pan smoothly to the location
                    map.panTo(position);
                }
            });

            // Initialize search box
            searchInput = document.getElementById('location-search');
            searchBox = new google.maps.places.Autocomplete(searchInput, {
                componentRestrictions: { country: 'my' }, // Restrict to Malaysia
            });

            // Bind the search box to the map
            searchBox.bindTo('bounds', map);

            // Listen for place selection
            searchBox.addListener('place_changed', function() {
                const place = searchBox.getPlace();

                if (!place.geometry || !place.geometry.location) {
                    return;
                }

                // If the place has a geometry, then present it on a map
                if (place.geometry.viewport) {
                    map.fitBounds(place.geometry.viewport);
                } else {
                    map.setCenter(place.geometry.location);
                    map.setZoom(17);
                }

                // Clear the search input
                searchInput.value = '';
            });

            // Initialize markers and circles
            setupMarkersAndCircles();

            // If there are existing coordinates, zoom to them
            const checkInLat = document.getElementById('check_in_latitude').value;
            const checkInLng = document.getElementById('check_in_longitude').value;
            if (checkInLat && checkInLng) {
                map.setZoom(17); // Zoom in when locations exist
            }

            // Add click listener to map
            map.addListener("click", function(event) {
                const lat = event.latLng.lat();
                const lng = event.latLng.lng();

                if (selectionMode === 'check-in') {
                    updateCheckInLocation(lat, lng);
                } else {
                    // Only allow setting check-out if check-in exists
                    const hasCheckIn = document.getElementById('check_in_latitude').value !== '';
                    if (hasCheckIn) {
                        updateCheckOutLocation(lat, lng);
                    } else {
                        alert('Please set check-in location first');
                        setSelectionMode('check-in');
                    }
                }
            });
        } catch (error) {
            console.error('Map initialization failed:', error);
        }
    }

    function setupMarkersAndCircles() {
        // Check-in marker (blue)
        checkInMarker = new google.maps.Marker({
            map: map,
            draggable: true,
            icon: {
                url: 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png',
                scaledSize: new google.maps.Size(35, 35)
            }
        });

        // Check-out marker (red)
        checkOutMarker = new google.maps.Marker({
            map: map,
            draggable: true,
            icon: {
                url: 'https://maps.google.com/mapfiles/ms/icons/red-dot.png',
                scaledSize: new google.maps.Size(35, 35)
            }
        });

        // Check-in circle
        checkInCircle = new google.maps.Circle({
            map: map,
            strokeColor: "#1742b4",
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: "#1742b4",
            fillOpacity: 0.15,
            radius: radiusInMeters
        });

        // Check-out circle
        checkOutCircle = new google.maps.Circle({
            map: map,
            strokeColor: "#dc2626",
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: "#dc2626",
            fillOpacity: 0.15,
            radius: radiusInMeters
        });

        // Set initial positions if available
        const checkInLat = document.getElementById('check_in_latitude').value;
        const checkInLng = document.getElementById('check_in_longitude').value;
        if (checkInLat && checkInLng) {
            updateCheckInLocation(parseFloat(checkInLat), parseFloat(checkInLng));
        }

        const checkOutLat = document.getElementById('check_out_latitude').value;
        const checkOutLng = document.getElementById('check_out_longitude').value;
        if (checkOutLat && checkOutLng) {
            updateCheckOutLocation(parseFloat(checkOutLat), parseFloat(checkOutLng));
        }

        // Add drag listeners
        checkInMarker.addListener('dragend', function(event) {
            updateCheckInLocation(event.latLng.lat(), event.latLng.lng());
        });

        checkOutMarker.addListener('dragend', function(event) {
            updateCheckOutLocation(event.latLng.lat(), event.latLng.lng());
        });
    }

    function updateCheckInLocation(lat, lng) {
        const position = { lat, lng };
        checkInMarker.setPosition(position);
        checkInCircle.setCenter(position);
        document.getElementById('check_in_latitude').value = lat.toFixed(6);
        document.getElementById('check_in_longitude').value = lng.toFixed(6);

        // Pan map to check-in location
        map.panTo(position);
    }

    function updateCheckOutLocation(lat, lng) {
        const position = { lat, lng };
        checkOutMarker.setPosition(position);
        checkOutCircle.setCenter(position);
        document.getElementById('check_out_latitude').value = lat.toFixed(6);
        document.getElementById('check_out_longitude').value = lng.toFixed(6);

        // Pan map to check-out location
        map.panTo(position);
    }

    function resetSettings() {
        // Create and submit a form to the reset endpoint
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route('admin.attendance.reset') }}';
        
        // Add CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        document.body.appendChild(form);
        form.submit();
    }

    function setSelectionMode(mode) {
        selectionMode = mode;
        updateToggleButtons();
    }

    function updateToggleButtons() {
        // Reset both buttons
        checkInToggle.classList.remove('bg-blue-100', 'text-blue-700', 'bg-gray-100', 'text-gray-700');
        checkOutToggle.classList.remove('bg-red-100', 'text-red-700', 'bg-gray-100', 'text-gray-700');

        // Style active button
        if (selectionMode === 'check-in') {
            checkInToggle.classList.add('bg-blue-100', 'text-blue-700');
            checkOutToggle.classList.add('bg-gray-100', 'text-gray-700');
        } else {
            checkOutToggle.classList.add('bg-red-100', 'text-red-700');
            checkInToggle.classList.add('bg-gray-100', 'text-gray-700');
        }
    }

    // Call this after initialization
    updateToggleButtons();
</script>
</body>
</html>

