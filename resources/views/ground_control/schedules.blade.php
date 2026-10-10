@extends('layouts.app')

@section('title', 'Dispatch Dashboard - runwayhub')

@section('content')
<div class="space-y-6">
    
    <!-- Dispatch Header -->
    <div class="bg-gradient-to-r from-blue-900 to-cyan-900 rounded-xl p-8 shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white flex items-center gap-3">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Dispatch Dashboard
                </h1>
                <p class="text-blue-200 mt-2">Erstelle & verwalte Flugpläne für deine Airline</p>
            </div>
            
            <!-- Quick Actions -->
            <div class="flex items-center gap-3">
                <button @click="$dispatch('create-schedule')" 
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Neuer Flugplan
                </button>
                
                <button class="bg-slate-700 hover:bg-slate-600 text-white px-4 py-3 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Filter & Search -->
    <div class="bg-slate-800 rounded-xl p-4 shadow-lg">
        <div class="flex flex-col md:flex-row gap-3 items-center">
            <!-- Date Range -->
            <div class="flex items-center gap-2 w-full md:w-auto">
                <label class="text-slate-300 text-sm">Datum:</label>
                <input type="date" class="bg-slate-700 border border-slate-600 text-white px-3 py-1.5 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                
                <span class="text-slate-400">→</span>
                
                <input type="date" class="bg-slate-700 border border-slate-600 text-white px-3 py-1.5 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
            </div>
            
            <!-- Search -->
            <div class="flex items-center gap-2 w-full md:w-auto">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" placeholder="Suche nach Callsign, Route..." 
                       class="bg-slate-700 border border-slate-600 text-white px-3 py-1.5 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary w-full">
            </div>
            
            <!-- Status Filter -->
            <div class="flex items-center gap-2 w-full md:w-auto">
                <span class="text-slate-300 text-sm">Status:</span>
                <select class="bg-slate-700 border border-slate-600 text-white px-3 py-1.5 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                    <option value="all">Alle</option>
                    <option value="active">Aktiv</option>
                    <option value="pending">Ausstehend</option>
                    <option value="completed">Abgeschlossen</option>
                </select>
            </div>
        </div>
    </div>
    
    <!-- Flight Schedules Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
        
        <!-- Schedule Card Template -->
        <template x-for="schedule in schedules" :key="schedule.id">
            <div 
                :class="{
                    'border-l-4 border-l-green-500': schedule.status === 'active',
                    'border-l-yellow-500': schedule.status === 'pending',
                    'border-l-slate-500': schedule.status === 'completed'
                }"
                class="bg-slate-800 rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300">
                
                <!-- Schedule Info -->
                <div class="mb-4">
                    <h3 class="text-2xl font-bold text-white mb-2" x-text="schedule.callsign"></h3>
                    
                    <div class="flex items-center justify-between text-slate-300">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span x-text="schedule.from"></span>
                        </span>
                        
                        <span class="text-slate-500">→</span>
                        
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 14l9-5m-9 5l9 5m-9-5v10a8 8 0 008 8h2"/>
                            </svg>
                            <span x-text="schedule.to"></span>
                        </span>
                    </div>
                </div>
                
                <!-- Schedule Details -->
                <div class="grid grid-cols-2 gap-3 mb-4 text-sm">
                    <div class="bg-slate-700/50 rounded-lg p-3">
                        <p class="text-slate-400 text-xs mb-1">Abflug</p>
                        <p class="text-white font-medium" x-text="formatDate(schedule.departure)"></p>
                    </div>
                    
                    <div class="bg-slate-700/50 rounded-lg p-3">
                        <p class="text-slate-400 text-xs mb-1">Ankunft</p>
                        <p class="text-white font-medium" x-text="formatDate(schedule.arrival)"></p>
                    </div>
                    
                    <div class="bg-slate-700/50 rounded-lg p-3">
                        <p class="text-slate-400 text-xs mb-1">Flugzeug</p>
                        <p class="text-white font-medium" x-text="schedule.aircraft"></p>
                    </div>
                    
                    <div class="bg-slate-700/50 rounded-lg p-3">
                        <p class="text-slate-400 text-xs mb-1">Entfernung</p>
                        <p class="text-white font-medium" x-text="schedule.distance + ' NM'"></p>
                    </div>
                </div>
                
                <!-- Status Badge & Actions -->
                <div class="flex items-center justify-between mt-4">
                    <span 
                        :class="{
                            'bg-green-500/20 text-green-300': schedule.status === 'active',
                            'bg-yellow-500/20 text-yellow-300': schedule.status === 'pending',
                            'bg-slate-500/20 text-slate-300': schedule.status === 'completed'
                        }"
                        class="px-4 py-2 rounded-full text-sm font-medium capitalize">
                        <span x-text="schedule.status"></span>
                    </span>
                    
                    <!-- Actions -->
                    <div class="flex gap-2">
                        <button @click="$dispatch('view-schedule', { schedule })" 
                                class="bg-slate-600 hover:bg-slate-500 text-white px-3 py-1.5 rounded-lg text-sm transition-colors">
                            Details
                        </button>
                        
                        <template x-if="schedule.status === 'active'">
                            <button @click="$dispatch('assign-pilot', { schedule })" 
                                    class="bg-primary hover:bg-blue-600 text-white px-3 py-1.5 rounded-lg text-sm transition-colors">
                                Pilot zuweisen
                            </button>
                        </template>
                        
                        <template x-if="schedule.status === 'pending'">
                            <button @click="$dispatch('activate-schedule', { schedule })" 
                                    class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded-lg text-sm transition-colors">
                                Aktivieren
                            </button>
                        </template>
                    </div>
                </div>
            </div>
        </template>
        
        <!-- Empty State -->
        <template x-if="schedules.length === 0">
            <div class="col-span-full text-center py-16 bg-slate-800/50 rounded-xl">
                <svg class="w-20 h-20 text-slate-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7M9 5a2 2 0 002-2h2a2 2 0 002 2m-3 6h3m-3 4h3"/>
                </svg>
                <p class="text-slate-400 text-lg mb-2">Keine Flugpläne gefunden</p>
                <p class="text-slate-500 text-sm">Erstelle deinen ersten Flugplan</p>
            </div>
        </template>
    </div>
    
    <!-- Route Map Preview -->
    <div class="bg-slate-800 rounded-xl p-6 shadow-lg">
        <h2 class="text-xl font-bold text-white mb-4 flex items-center gap-3">
            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
            </svg>
            Routenübersicht
        </h2>
        
        <!-- Map Placeholder -->
        <div class="bg-slate-700/50 rounded-lg h-64 flex items-center justify-center">
            <p class="text-slate-400 text-center px-4">
                <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                </svg>
                Interaktive Routenkarte (Leaflet/Mapbox Integration)
            </p>
        </div>
    </div>
    
</div>

@push('scripts')
<script>
Alpine.data('schedules', () => ({
    schedules: [],
    
    init() {
        this.loadSchedules();
    },
    
    loadSchedules() {
        // Simulated data - replace with API call
        this.schedules = [
            {
                id: 1,
                callsign: 'RWH-001',
                from: 'EGKK (London)',
                to: 'LHBB (Berlin)',
                departure: '2026-10-15 08:00',
                arrival: '2026-10-15 09:30',
                aircraft: 'A320',
                distance: 450,
                status: 'active'
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
                status: 'pending'
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
