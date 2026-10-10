<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'runwayhub - Flight Simulator Virtual Airline')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Custom Styles -->
    <style>
        [x-cloak] { display: none !important; }
        
        .flight-card {
            @apply bg-slate-800 rounded-lg p-6 shadow-lg hover:shadow-xl transition-all duration-300;
        }
        
        .weather-widget {
            @apply bg-gradient-to-r from-blue-900 to-indigo-900 rounded-lg p-4;
        }
        
        .schedule-row {
            @apply border-b border-slate-700 hover:bg-slate-800/50 transition-colors;
        }
    </style>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3B82F6',
                        secondary: '#10B981',
                        accent: '#F59E0B'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen">
    
    <!-- Navigation -->
    <nav x-data="{ open: false }" class="bg-slate-800 border-b border-slate-700 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="text-2xl font-bold text-primary">🌉 runwayhub</a>
                    
                    <!-- Desktop Menu -->
                    <div class="hidden md:ml-10 md:flex space-x-8">
                        <a href="{{ route('pilots.dashboard') }}" class="text-slate-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('pilots.*') ? 'text-white bg-slate-700' : '' }}">Dashboard</a>
                        <a href="{{ route('pilots.flights.index') }}" class="text-slate-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('pilots.flights.*') ? 'text-white bg-slate-700' : '' }}">Flüge</a>
                        <a href="{{ route('pilots.logs') }}" class="text-slate-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('pilots.logs.*') ? 'text-white bg-slate-700' : '' }}">Logs</a>
                        <a href="{{ route('ground_control.schedules') }}" class="text-slate-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Dispatch</a>
                    </div>
                </div>
                
                <!-- Auth Buttons -->
                <div class="flex items-center space-x-4">
                    @auth
                        <span x-data="{ showDropdown: false }" 
                              x-show="showDropdown"
                              x-transition 
                              @click.away="showDropdown = false"
                              class="absolute right-4 top-10 bg-slate-800 rounded-lg shadow-lg p-4 w-64">
                            <p class="font-medium">{{ auth()->user()->name }}</p>
                            <p class="text-sm text-slate-400">{{ auth()->user()->email }}</p>
                            <p class="text-xs text-slate-500 mt-1">Role: {{ auth()->user()->role ?? 'pilot' }}</p>
                        </span>
                        <button @click="showDropdown = !showDropdown" 
                                class="relative p-2 rounded-lg text-slate-300 hover:text-white hover:bg-slate-700 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span class="absolute top-0 right-0 -mt-1 -mr-1 flex h-3 w-3">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                            </span>
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="text-slate-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Login</a>
                        <a href="{{ route('register') }}" class="bg-primary hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">Sign Up</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>
    
    <!-- Footer -->
    <footer class="bg-slate-800 border-t border-slate-700 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="text-center text-slate-400">
                <p>&copy; {{ date('Y') }} runwayhub - Flight Simulator Virtual Airline Community</p>
                <p class="mt-2 text-sm">Für MSFS und X-Plane Piloten 🛫</p>
            </div>
        </div>
    </footer>
    
    @stack('scripts')
</body>
</html>
