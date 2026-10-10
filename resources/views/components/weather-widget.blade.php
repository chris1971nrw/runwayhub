@props(['location' => null, 'apiKey' => config('services.open_meteo.api_key')])

<div 
    x-data="weatherWidget(@entangle('loading').get)" 
    x-init="initWeatherWidget()" 
    class="weather-widget bg-gradient-to-r from-blue-900 to-indigo-900 rounded-lg p-4 shadow-md">
    
    <!-- Loading State -->
    <template x-if="loading">
        <div class="flex items-center justify-center py-8">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-white mx-auto"></div>
            <p class="text-white ml-3 text-sm">Wetterdaten laden...</p>
        </div>
    </template>
    
    <!-- Error State -->
    <template x-if="error && !loading">
        <div class="bg-red-900/50 rounded-lg p-4 text-center">
            <svg class="w-8 h-8 text-red-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <p class="text-red-200 text-sm" x-text="error"></p>
        </div>
    </template>
    
    <!-- Weather Display -->
    <template x-if="!loading && !error">
        <div class="flex items-center justify-between">
            
            <!-- Location & Status -->
            <div class="flex-1">
                <h3 class="text-white font-semibold mb-2 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.7-9.9 5 5 0 10-1.4 10.9M3 15a4 4 0 004 4h9a5 5 0 10-.7-9.9 5 5 0 10-1.4 10.9"/>
                    </svg>
                    <span x-text="locationName"></span>
                </h3>
                
                <!-- Weather Icon & Conditions -->
                <div class="flex items-center gap-3">
                    <svg 
                        :class="{
                            'text-blue-200': weatherCondition === 'clear',
                            'text-yellow-200': weatherCondition === 'partly_cloudy',
                            'text-gray-200': weatherCondition === 'overcast',
                            'text-purple-200': weatherCondition === 'rain',
                            'text-orange-200': weatherCondition === 'fog'
                        }"
                        class="w-16 h-16 flex-shrink-0"
                        fill="none" 
                        stroke="currentColor" 
                        viewBox="0 0 24 24">
                        <template x-if="weatherCondition === 'clear'">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.7-9.9 5 5 0 10-1.4 10.9M3 15a4 4 0 004 4h9a5 5 0 10-.7-9.9 5 5 0 10-1.4 10.9"/>
                        </template>
                        
                        <template x-if="weatherCondition === 'partly_cloudy'">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.7-9.9 5 5 0 10-1.4 10.9M18.378 8.254L12.623 1.526a2.25 2.25 0 01-2.21-.47l-1.225 1.225c-.932.932-1.582 2.193-1.582 3.553V19.5a2.25 2.25 0 002.25 2.25h10.5a2.25 2.25 0 002.25-2.25V10.5A2.25 2.25 0 0018.378 8.254z"/>
                        </template>
                        
                        <template x-if="weatherCondition === 'overcast'">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-1.084.144-2.196-.52-2.515-1.546a4.5 4.5 0 01-.548-2.232c0-1.278.91-2.356 2.135-2.416A4.5 4.5 0 0120.25 14.15zM3.75 14.15v4.25c0 1.094.787 2.036 1.872 2.18 1.084.144 2.196-.52 2.515-1.546a4.5 4.5 0 01.548-2.232c0-1.278-.91-2.356-2.135-2.416A4.5 4.5 0 013.75 14.15zM16 11a1 1 0 10-2 0 1 1 0 002 0zm-6 0a1 1 0 10-2 0 1 1 0 002 0z"/>
                        </template>
                        
                        <template x-if="weatherCondition === 'rain'">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 9v3m4.5-3v3m-9 3h18a2 2 0 002-2V7.5A2.5 2.5 0 0016.5 5H2.5A2.5 2.5 0 000 7.5v4.5a2 2 0 002 2zm9-3h.008v.008H12V6z"/>
                        </template>
                        
                        <template x-if="weatherCondition === 'fog'">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                        </template>
                    </svg>
                    
                    <div class="flex flex-col">
                        <span class="text-white text-3xl font-bold" x-text="temperature + '°C'"></span>
                        <p class="text-blue-200 text-sm capitalize" x-text="weatherCondition || 'Unknown'"></p>
                    </div>
                </div>
            </div>
            
            <!-- Wind & Visibility -->
            <div class="flex items-center gap-4 text-right">
                
                <!-- Wind Speed -->
                <div class="text-center min-w-[70px]">
                    <svg class="w-5 h-5 text-blue-200 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <span class="text-white text-sm font-medium" x-text="windSpeed + ' km/h'"></span>
                </div>
                
                <!-- Visibility -->
                <div class="text-center min-w-[70px]">
                    <svg class="w-5 h-5 text-blue-200 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.543 7-1.274 4.057-5.064 7-9.543 7-4.477 0-8.268-2.943-9.543-7z"/>
                    </svg>
                    <span class="text-white text-sm font-medium" x-text="visibility + ' km'"></span>
                </div>
                
            </div>
        </div>
        
        <!-- Flight Suitability -->
        <div class="mt-4 bg-slate-800/50 rounded-lg p-3">
            <p class="text-white text-sm mb-2 flex items-center gap-2">
                <svg class="w-5 h-5" :class="{ 'text-green-400': isVfr, 'text-yellow-400': !isVfr }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span x-text="isVfr ? '✅ VFR Bedingungen - Flug empfohlen' : '⚠️ IFR Bedingungen - Vorsicht!'"></span>
            </p>
            
            <!-- VFR Threshold Warning -->
            <template x-if="!isVfr">
                <div class="bg-yellow-900/50 rounded-lg p-2 mt-2">
                    <p class="text-yellow-200 text-xs">
                        ⚠️ Mindestsicht: <span x-text="vfrMinimumVisibility + ' km'"></span> | 
                        Mindest-Wolkenbasis: <span x-text="vfrMinimumCeiling + ' ft'"></span>
                    </p>
                </div>
            </template>
        </div>
    </div>
    
</div>

@push('scripts')
<script>
function weatherWidget(loading) {
    return {
        loading,
        locationName: 'Berlin (LHBB)',
        temperature: 12,
        windSpeed: 15,
        visibility: 10,
        weatherCondition: 'partly_cloudy',
        isVfr: true,
        vfrMinimumVisibility: 5,
        vfrMinimumCeiling: 1000,
        
        initWeatherWidget() {
            if (!this.location) return;
            
            // Fetch weather data from Open-Meteo API
            fetch(`https://api.open-meteo.com/v1/forecast?latitude=52.5&longitude=13.4&current_weather=true`)
                .then(response => response.json())
                .then(data => {
                    this.temperature = Math.round(data.current_weather.temperature);
                    this.windSpeed = data.current_weather.windspeed;
                    this.visibility = data.current_weather.cloudcover > 75 ? '2' : '10';
                    
                    // Determine weather condition based on cloud cover
                    const cloudCover = data.current_weather.cloudcover;
                    if (cloudCover < 25) {
                        this.weatherCondition = 'clear';
                    } else if (cloudCover < 75) {
                        this.weatherCondition = 'partly_cloudy';
                    } else {
                        this.weatherCondition = 'overcast';
                    }
                    
                    // Check VFR conditions
                    this.isVfr = cloudCover < 75 && this.visibility >= this.vfrMinimumVisibility;
                })
                .catch(error => {
                    console.error('Weather API error:', error);
                    this.loading = false;
                    this.error = 'Wetterdaten nicht verfügbar';
                });
        }
    };
}
</script>
@endpush
