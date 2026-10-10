@extends('layouts.app')

@section('title', 'Flug Details - runwayhub')

@section('content')
<div class="space-y-6">
    
    <!-- Flight Details Header -->
    <div class="bg-slate-800 rounded-xl p-6 shadow-lg">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-2xl font-bold text-white flex items-center gap-3">
                    <svg class="w-7 h-7 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 19.477 5.754 20 7.5 20s3.332-.523 4.5-1.277m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.523 4.5 1.277v13C19.832 19.477 18.247 20 16.5 20c-1.746 0-3.332-.523-4.5-1.277z"/>
                    </svg>
                    <span x-text="flight.callsign"></span>
                </h1>
                <p class="text-slate-400 mt-1">Route: <span x-text="flight.from" class="text-white font-medium"></span> → <span x-text="flight.to" class="text-white font-medium"></span></p>
            </div>
            
            <!-- Status Badge -->
            <span 
                :class="{
                    'bg-green-500/20 text-green-300': flight.status === 'completed',
                    'bg-yellow-500/20 text-yellow-300': flight.status === 'pending',
                    'bg-red-500/20 text-red-300': flight.status === 'cancelled'
                }"
                class="px-6 py-3 rounded-full text-lg font-medium capitalize">
                <span x-text="flight.status"></span>
            </span>
        </div>
        
        <!-- Flight Route Map -->
        <div class="bg-slate-700/50 rounded-lg p-4 h-64 flex items-center justify-center mb-4">
            <p class="text-slate-400 text-center px-4">
                <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                </svg>
                Routenkarte (Leaflet/Mapbox Integration)
            </p>
        </div>
        
        <!-- Flight Details Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-slate-700/50 rounded-lg p-4 text-center">
                <p class="text-slate-400 text-xs mb-1">Abflug</p>
                <p class="text-white font-medium" x-text="formatDate(flight.departure)"></p>
            </div>
            
            <div class="bg-slate-700/50 rounded-lg p-4 text-center">
                <p class="text-slate-400 text-xs mb-1">Ankunft</p>
                <p class="text-white font-medium" x-text="formatDate(flight.arrival)"></p>
            </div>
            
            <div class="bg-slate-700/50 rounded-lg p-4 text-center">
                <p class="text-slate-400 text-xs mb-1">Flugzeug</p>
                <p class="text-white font-medium" x-text="flight.aircraft"></p>
            </div>
            
            <div class="bg-slate-700/50 rounded-lg p-4 text-center">
                <p class="text-slate-400 text-xs mb-1">Entfernung</p>
                <p class="text-white font-medium" x-text="flight.distance + ' NM'"></p>
            </div>
        </div>
    </div>
    
    <!-- Log Upload Form (only for completed flights) -->
    <template x-if="flight.status === 'completed'">
        <div class="bg-gradient-to-r from-blue-900/80 to-indigo-900/80 rounded-xl p-6 shadow-lg">
            <h2 class="text-xl font-bold text-white mb-4 flex items-center gap-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Flugprotokoll hochladen
            </h2>
            
            <form @submit.prevent="$dispatch('submit-flight-log')" class="space-y-4">
                
                <!-- Comments -->
                <div>
                    <label for="comments" class="block text-sm font-medium text-white mb-2">Kommentare (optional)</label>
                    <textarea 
                        id="comments" 
                        name="comments" 
                        rows="3" 
                        class="w-full bg-slate-800 border border-slate-600 text-white pl-4 pr-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary placeholder-slate-400 resize-none"
                        placeholder="Flugverlauf, Wetterbedingungen, Besonderheiten..."></textarea>
                </div>
                
                <!-- Screenshot Upload -->
                <div class="bg-slate-800/50 rounded-lg p-4">
                    <label class="block text-sm font-medium text-white mb-2">Screenshots hochladen (optional)</label>
                    <div class="border-2 border-dashed border-slate-600 rounded-lg p-6 text-center hover:border-primary transition-colors cursor-pointer" @click="$dispatch('trigger-file-upload')">
                        <svg class="w-12 h-12 text-slate-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-slate-400 text-sm mb-1">Screenshots hier ablegen oder klicken zum Auswählen</p>
                        <p class="text-xs text-slate-500">JPG, PNG bis zu 10MB pro Datei</p>
                        <input type="file" id="screenshots" multiple accept="image/*" class="hidden" @change="$dispatch('handle-file-select', $event)">
                    </div>
                </div>
                
                <!-- Weather Conditions -->
                <div>
                    <label for="weather" class="block text-sm font-medium text-white mb-2">Wetterbedingungen</label>
                    <select id="weather" name="weather_conditions" class="w-full bg-slate-800 border border-slate-600 text-white px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                        <option value="">Wetterbedingungen auswählen...</option>
                        <option value="vfr_clear">VFR - Klarer Himmel</option>
                        <option value="vfr_overcast">VFR - Bewölkt</option>
                        <option value="ifr_moderate">IFR - Mäßige Sicht</option>
                        <option value="ifr_poor">IFR - Schlechte Sicht</option>
                    </select>
                </div>
                
                <!-- Flight Rating -->
                <div>
                    <label for="rating" class="block text-sm font-medium text-white mb-2">Pilot Rating ⭐</label>
                    <div class="flex items-center gap-2">
                        <input type="range" id="rating" name="flight_rating" min="1" max="5" step="0.1" value="4.5" class="w-full h-2 bg-slate-700 rounded-lg appearance-none cursor-pointer accent-primary">
                        <span class="text-accent font-bold text-xl w-16 text-right" x-text="rating + ' ⭐'"></span>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full bg-primary hover:bg-blue-600 text-white font-medium py-3 rounded-lg transition-colors flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Protokoll speichern
                </button>
            </form>
        </div>
    </template>
    
    <!-- Flight Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- On-Time Performance -->
        <div class="bg-slate-800 rounded-xl p-6 shadow-lg">
            <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                On-Time Performance
            </h3>
            
            <div class="flex items-center justify-center">
                <div class="relative w-40 h-40">
                    <svg class="w-full h-full transform -rotate-90">
                        <circle cx="50%" cy="50%" r="40%" fill="none" stroke="#334155" stroke-width="8"></circle>
                        <circle 
                            :class="{
                                'text-green-500': onTimePercentage >= 90,
                                'text-yellow-500': onTimePercentage >= 70,
                                'text-red-500': onTimePercentage < 70
                            }"
                            cx="50%" cy="50%" r="40%" fill="none" stroke-width="8"
                            :stroke-dasharray="getCircleCircumference() * (onTimePercentage / 100)"
                            :stroke-dashoffset="0">
                        </circle>
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="text-3xl font-bold text-white" x-text="Math.round(onTimePercentage) + '%'"></span>
                    </div>
                </div>
            </div>
            
            <p class="text-slate-400 text-sm mt-4 text-center">
                Vergleich mit geschätzter Abflugzeit
            </p>
        </div>
        
        <!-- Fuel Efficiency -->
        <div class="bg-slate-800 rounded-xl p-6 shadow-lg">
            <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                Kraftstoffeffizienz
            </h3>
            
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-slate-300 text-sm">Verbrauch</span>
                    <span class="text-white font-medium" x-text="fuelConsumption + ' gal'"></span>
                </div>
                
                <div class="flex items-center justify-between">
                    <span class="text-slate-300 text-sm">Effizienz</span>
                    <span class="text-green-400 font-medium" x-text="fuelEfficiency + ' gal/100NM'"></span>
                </div>
                
                <!-- Progress Bar -->
                <div class="mt-4 bg-slate-700 rounded-full h-2">
                    <div 
                        :class="{
                            'bg-green-500': fuelEfficiency <= 8,
                            'bg-yellow-500': fuelEfficiency > 8 && fuelEfficiency <= 10,
                            'bg-red-500': fuelEfficiency > 10
                        }"
                        class="h-full rounded-full transition-all duration-300"
                        :style="{ width: (100 - (fuelEfficiency - 5) * 5) + '%' }">
                    </div>
                </div>
                
                <p class="text-slate-400 text-xs text-center mt-2">
                    Optimal: ≤8 gal/100NM
                </p>
            </div>
        </div>
        
        <!-- Community Reviews -->
        <div class="bg-slate-800 rounded-xl p-6 shadow-lg">
            <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                </svg>
                Community Bewertung
            </h3>
            
            <!-- Rating Stars -->
            <div class="flex items-center justify-center gap-1 mb-4">
                <template x-for="star in 5">
                    <svg 
                        :class="{
                            'text-yellow-500': star <= rating,
                            'text-slate-600': star > rating
                        }"
                        class="w-8 h-8"
                        fill="currentColor"
                        viewBox="0 0 24 24">
                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                    </svg>
                </template>
            </div>
            
            <!-- Review Count -->
            <p class="text-slate-400 text-sm text-center">
                <span x-text="reviewCount"></span> Bewertungen von Piloten
            </p>
            
            <!-- Latest Reviews -->
            <div class="mt-4 space-y-2">
                <template x-for="review in reviews" :key="review.id">
                    <div class="bg-slate-700/50 rounded-lg p-3">
                        <p class="text-slate-200 text-sm" x-text="review.comment"></p>
                        <p class="text-slate-400 text-xs mt-1" x-text="review.pilot + ' ⭐'"></p>
                    </div>
                </template>
            </div>
        </div>
    </div>
    
</div>

@push('scripts')
<script>
Alpine.data('flight', () => ({
    flight: {
        id: 1,
        callsign: 'RWH-001',
        from: 'EGKK (London)',
        to: 'LHBB (Berlin)',
        departure: '2026-10-15 08:00',
        arrival: '2026-10-15 09:30',
        aircraft: 'A320',
        distance: 450,
        status: 'completed'
    },
    
    onTimePercentage: 95,
    fuelConsumption: 45,
    fuelEfficiency: 10,
    rating: 4.5,
    reviewCount: 23,
    reviews: [
        { id: 1, comment: 'Perfekt abgelaufen!', pilot: 'ALPHA 1' },
        { id: 2, comment: 'Wetter war besser als erwartet', pilot: 'BRAVO 2' }
    ],
    
    formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('de-DE', {
            day: '2-digit',
            month: 'short',
            hour: '2-digit',
            minute: '2-digit'
        });
    },
    
    getCircleCircumference() {
        return 2 * Math.PI * 40;
    }
}));
</script>
@endpush
@endsection
