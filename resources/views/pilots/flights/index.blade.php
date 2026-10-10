@extends('layouts.app')

@section('title', 'Meine Flüge - runwayhub')

@section('content')
<div class="space-y-6">
    
    <!-- Page Header -->
    <div class="bg-slate-800 rounded-xl p-6 shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white flex items-center gap-3">
                    <svg class="w-7 h-7 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 19.477 5.754 20 7.5 20s3.332-.523 4.5-1.277m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.523 4.5 1.277v13C19.832 19.477 18.247 20 16.5 20c-1.746 0-3.332-.523-4.5-1.277z"/>
                    </svg>
                    Meine Flüge
                </h1>
                <p class="text-slate-400 mt-1">Verwalte deine Buchungen und Flugprotokolle</p>
            </div>
            
            <!-- Filter Controls -->
            <div class="flex items-center gap-3">
                <select class="bg-slate-700 border border-slate-600 text-white px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                    <option value="all">Alle Flüge</option>
                    <option value="pending">Ausstehend</option>
                    <option value="completed">Abgeschlossen</option>
                    <option value="cancelled">Storniert</option>
                </select>
                
                <button class="bg-slate-700 hover:bg-slate-600 text-white px-4 py-2 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.5c0 .9-.3 1.7-.8 2.3L13 11v6a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Flight Cards Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
        
        <!-- Flight Card Template -->
        <template x-for="flight in flights" :key="flight.id">
            <div 
                :class="{
                    'border-l-4 border-l-blue-500': flight.status === 'pending',
                    'border-l-green-500': flight.status === 'completed',
                    'border-l-red-500': flight.status === 'cancelled'
                }"
                class="bg-slate-800 rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300">
                
                <!-- Flight Info -->
                <div class="mb-4">
                    <h3 class="text-xl font-bold text-white mb-2" x-text="flight.callsign"></h3>
                    
                    <div class="flex items-center justify-between text-slate-300">
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
                </div>
                
                <!-- Flight Details -->
                <div class="grid grid-cols-2 gap-4 mb-4 text-sm">
                    <div class="bg-slate-700/50 rounded-lg p-3">
                        <p class="text-slate-400 text-xs mb-1">Abflug</p>
                        <p class="text-white font-medium" x-text="formatDate(flight.departure)"></p>
                    </div>
                    
                    <div class="bg-slate-700/50 rounded-lg p-3">
                        <p class="text-slate-400 text-xs mb-1">Ankunft</p>
                        <p class="text-white font-medium" x-text="formatDate(flight.arrival)"></p>
                    </div>
                    
                    <div class="bg-slate-700/50 rounded-lg p-3">
                        <p class="text-slate-400 text-xs mb-1">Flugzeug</p>
                        <p class="text-white font-medium" x-text="flight.aircraft"></p>
                    </div>
                    
                    <div class="bg-slate-700/50 rounded-lg p-3">
                        <p class="text-slate-400 text-xs mb-1">Entfernung</p>
                        <p class="text-white font-medium" x-text="flight.distance + ' NM'"></p>
                    </div>
                </div>
                
                <!-- Status Badge -->
                <div class="flex items-center justify-between mt-4">
                    <span 
                        :class="{
                            'bg-yellow-500/20 text-yellow-300': flight.status === 'pending',
                            'bg-green-500/20 text-green-300': flight.status === 'completed',
                            'bg-red-500/20 text-red-300': flight.status === 'cancelled'
                        }"
                        class="px-4 py-2 rounded-full text-sm font-medium capitalize">
                        <span x-text="flight.status"></span>
                    </span>
                    
                    <!-- Actions -->
                    <div class="flex gap-2">
                        <button @click="$dispatch('view-flight', { flight })" 
                                class="bg-slate-600 hover:bg-slate-500 text-white px-3 py-1.5 rounded-lg text-sm transition-colors">
                            Details
                        </button>
                        
                        <template x-if="flight.status === 'pending'">
                            <button @click="$dispatch('cancel-flight', { flight })" 
                                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded-lg text-sm transition-colors">
                                Stornieren
                            </button>
                        </template>
                        
                        <template x-if="flight.status === 'completed'">
                            <button @click="$dispatch('submit-log', { flight })" 
                                    class="bg-primary hover:bg-blue-600 text-white px-3 py-1.5 rounded-lg text-sm transition-colors">
                                Log hochladen
                            </button>
                        </template>
                    </div>
                </div>
            </div>
        </template>
        
        <!-- Empty State -->
        <template x-if="flights.length === 0">
            <div class="col-span-full text-center py-16 bg-slate-800/50 rounded-xl">
                <svg class="w-20 h-20 text-slate-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v3a2 2 0 01-2 2H5a2 2 0 01-2-2v-3a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                <p class="text-slate-400 text-lg mb-2">Keine Flüge gefunden</p>
                <p class="text-slate-500 text-sm">Warte auf Zuweisung durch Ground Control oder buche einen neuen Flug</p>
            </div>
        </template>
    </div>
    
    <!-- Pagination -->
    <div class="flex items-center justify-between bg-slate-800 rounded-xl p-4 shadow-lg">
        <p class="text-slate-400 text-sm">Zeige <span class="text-white font-medium">1</span> bis <span class="text-white font-medium" x-text="flights.length"></span> von <span class="text-white font-medium">5</span> Ergebnissen</p>
        
        <div class="flex gap-2">
            <button :disabled="currentPage === 1" 
                    @click="currentPage--"
                    class="bg-slate-700 hover:bg-slate-600 disabled:bg-slate-800 disabled:text-slate-500 text-white px-4 py-2 rounded-lg transition-colors">
                Vorgänger
            </button>
            
            <span class="bg-slate-700 text-white px-4 py-2 rounded-lg">Seite <span x-text="currentPage"></span></span>
            
            <button :disabled="currentPage === totalPages" 
                    @click="currentPage++"
                    class="bg-slate-700 hover:bg-slate-600 disabled:bg-slate-800 disabled:text-slate-500 text-white px-4 py-2 rounded-lg transition-colors">
                Nächster
            </button>
        </div>
    </div>
    
</div>

@push('scripts')
<script>
Alpine.data('flights', () => ({
    flights: [],
    currentPage: 1,
    totalPages: 5,
    
    init() {
        this.loadFlights();
    },
    
    loadFlights() {
        // Simulated data - replace with API call
        this.flights = [
            {
                id: 1,
                callsign: 'RWH-001',
                from: 'EGKK (London)',
                to: 'LHBB (Berlin)',
                departure: '2026-10-15 08:00',
                arrival: '2026-10-15 09:30',
                aircraft: 'A320',
                distance: 450,
                status: 'pending'
            },
            {
                id: 2,
                callsign: 'RWH-002',
                from: 'LHBB (Berlin)',
                to: 'EDDF (Frankfurt)',
                departure: '2026-10-18 14:00',
                arrival: '2026-10-18 14:45',
                aircraft: 'B738',
                distance: 320,
                status: 'completed'
            }
        ];
    },
    
    formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('de-DE', {
            day: '2-digit',
            month: 'short',
            hour: '2-digit',
            minute: '2-digit'
        });
    }
}));
</script>
@endpush
@endsection
