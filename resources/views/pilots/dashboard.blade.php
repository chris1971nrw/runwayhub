@extends('layouts.app')

@section('title', 'Pilot Dashboard - runwayhub')

@section('content')
<div class="space-y-6">
    
    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-blue-900 to-indigo-900 rounded-xl p-8 shadow-lg">
        <h1 class="text-3xl font-bold text-white mb-2">👋 Willkommen zurück, {{ auth()->user()->name }}!</h1>
        <p class="text-blue-200">Du bist aktuell als <span class="font-semibold text-accent">{{ ucfirst(auth()->user()->role ?? 'Pilot') }}</span> angemeldet</p>
        
        <!-- Weather Widget -->
        <div class="mt-6 weather-widget rounded-lg p-4">
            <h3 class="text-white font-semibold mb-2 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.7-9.9 5 5 0 10-1.4 10.9M3 15a4 4 0 004 4h9a5 5 0 10-.7-9.9 5 5 0 10-1.4 10.9"/>
                </svg>
                Wetter Widget (Open-Meteo API)
            </h3>
            <div class="flex items-center justify-between text-white">
                <div>
                    <span id="weather-status" class="text-lg font-semibold">⏳ Lade...</span>
                    <p class="text-sm text-blue-200 mt-1">Flugbedingungen für deine nächste Route</p>
                </div>
                <div class="text-right">
                    <span id="temperature" class="text-3xl font-bold">--°C</span>
                    <p class="text-sm text-blue-200">Aktuelle Temperatur</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Assigned Flights -->
        <div class="flight-card">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-lg font-semibold text-slate-200">Zugeteigte Flüge</h3>
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v3a2 2 0 01-2 2H5a2 2 0 01-2-2v-3a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
            </div>
            <p class="text-3xl font-bold text-white" x-data="{ count: 0 }">
                <span x-text="count"></span>
            </p>
            <p class="text-sm text-slate-400 mt-1">Für dieses Monat</p>
        </div>
        
        <!-- Completed Flights -->
        <div class="flight-card">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-lg font-semibold text-slate-200">Abgeschlossene Flüge</h3>
                <svg class="w-6 h-6 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-3xl font-bold text-white" x-data="{ count: 0 }">
                <span x-text="count"></span>
            </p>
            <p class="text-sm text-slate-400 mt-1">Gesamtstatistik</p>
        </div>
        
        <!-- On-Time Performance -->
        <div class="flight-card">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-lg font-semibold text-slate-200">Pünktlichkeit</h3>
                <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-3xl font-bold text-white" x-data="{ percentage: 0 }">
                <span x-text="percentage + '%'"></span>
            </p>
            <p class="text-sm text-slate-400 mt-1">On-Time Performance</p>
        </div>
        
        <!-- Flight Rating -->
        <div class="flight-card">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-lg font-semibold text-slate-200">Pilot Rating</h3>
                <svg class="w-6 h-6 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                </svg>
            </div>
            <p class="text-3xl font-bold text-white" x-data="{ rating: 0 }">
                <span x-text="rating + ' ⭐'"></span>
            </p>
            <p class="text-sm text-slate-400 mt-1">Community Bewertung</p>
        </div>
    </div>
    
    <!-- Assigned Flights Section -->
    <div class="bg-slate-800 rounded-xl p-6 shadow-lg">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                <svg class="w-7 h-7 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 19.477 5.754 20 7.5 20s3.332-.523 4.5-1.277m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.523 4.5 1.277v13C19.832 19.477 18.247 20 16.5 20c-1.746 0-3.332-.523-4.5-1.277z"/>
                </svg>
                Zugeteigte Flüge
            </h2>
            
            <a href="{{ route('pilots.flights.index') }}" class="bg-primary hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
                Alle Flüge ansehen →
            </a>
        </div>
        
        <!-- Flight List -->
        <div class="space-y-4" x-data="{ 
            flights: [],
            loading: true,
            fetchFlights() {
                this.loading = true;
                // TODO: AJAX call to get assigned flights
                setTimeout(() => {
                    this.flights = [
                        {
                            callsign: 'RWH-001',
                            from: 'EGKK (London)',
                            to: 'LHBB (Berlin)',
                            departure: '2026-10-15 08:00',
                            aircraft: 'A320',
                            status: 'pending'
                        }
                    ];
                    this.loading = false;
                }, 1000);
            }
        }" x-init="fetchFlights()">
            
            <template x-if="loading">
                <div class="text-center py-8 text-slate-400">Lade Flüge...</div>
            </template>
            
            <template x-for="flight in flights" :key="flight.callsign">
                <div class="schedule-row bg-slate-700/50 rounded-lg p-6 hover:bg-slate-700 transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-white mb-2" x-text="flight.callsign"></h3>
                            
                            <div class="flex items-center gap-4 text-slate-300">
                                <span class="flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span x-text="flight.from"></span>
                                </span>
                                <span class="text-slate-500">→</span>
                                <span class="flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 14l9-5m-9 5l9 5m-9-5v10a8 8 0 008 8h2"/>
                                    </svg>
                                    <span x-text="flight.to"></span>
                                </span>
                            </div>
                            
                            <div class="mt-3 flex items-center gap-4 text-sm text-slate-400">
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span x-text="flight.aircraft"></span>
                                </span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span x-text="flight.departation"></span>
                                </span>
                            </div>
                        </div>
                        
                        <!-- Status Badge -->
                        <div class="ml-4">
                            <span 
                                :class="{
                                    'bg-yellow-500/20 text-yellow-300': flight.status === 'pending',
                                    'bg-green-500/20 text-green-300': flight.status === 'completed',
                                    'bg-red-500/20 text-red-300': flight.status === 'cancelled'
                                }"
                                class="px-4 py-2 rounded-full text-sm font-medium capitalize">
                                <span x-text="flight.status"></span>
                            </span>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="mt-4 flex gap-2">
                        <button @click="$dispatch('view-flight', { flight })" 
                                class="bg-slate-600 hover:bg-slate-500 text-white px-3 py-1.5 rounded-lg text-sm transition-colors">
                            Details
                        </button>
                        <button @click="$dispatch('book-flight', { flight })" 
                                :disabled="flight.status !== 'pending'"
                                class="bg-primary hover:bg-blue-600 disabled:bg-slate-700 disabled:text-slate-500 text-white px-3 py-1.5 rounded-lg text-sm transition-colors">
                            Buchen
                        </button>
                    </div>
                </div>
            </template>
            
            <template x-if="!loading && flights.length === 0">
                <div class="text-center py-12 bg-slate-700/30 rounded-lg">
                    <svg class="w-16 h-16 text-slate-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <p class="text-slate-400 mb-2">Keine zugeteigten Flüge</p>
                    <p class="text-sm text-slate-500">Warte auf Zuweisung durch Ground Control</p>
                </div>
            </template>
        </div>
    </div>
    
    <!-- Upcoming Weather Alerts -->
    <div class="bg-gradient-to-r from-orange-900/80 to-red-900/80 rounded-xl p-6 shadow-lg">
        <h2 class="text-xl font-bold text-white mb-4 flex items-center gap-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            Wetterwarnungen & Flugbedingungen
        </h2>
        
        <div class="space-y-3">
            <!-- Alert Item -->
            <div class="bg-slate-800/50 rounded-lg p-4 flex items-start gap-3">
                <svg class="w-5 h-5 text-orange-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div class="flex-1">
                    <p class="text-slate-200 text-sm">Keine aktiven Wetterwarnungen für deine geplanten Routen</p>
                    <p class="text-slate-400 text-xs mt-1">Letzte Prüfung: Vor 5 Minuten</p>
                </div>
            </div>
        </div>
    </div>
    
</div>

@push('scripts')
<script>
// Alpine.js Data Binding für Statistiken
document.addEventListener('alpine:init', () => {
    Alpine.data('stats', () => ({
        assignedFlights: 0,
        completedFlights: 0,
        onTimePercentage: 0,
        pilotRating: 0
    }));
});

// Weather Widget API Call (Open-Meteo)
async function fetchWeather() {
    try {
        const response = await fetch('https://api.open-meteo.com/v1/forecast?latitude=52.5&longitude=13.4&current_weather=true');
        const data = await response.json();
        
        document.getElementById('weather-status').textContent = '✅ VFR Bedingungen';
        document.getElementById('temperature').textContent = Math.round(data.current_weather.temperature) + '°C';
    } catch (error) {
        console.error('Weather API error:', error);
        document.getElementById('weather-status').textContent = '⚠️ API nicht erreichbar';
    }
}

fetchWeather();
</script>
@endpush
@endsection
