@extends('layouts.app')

@section('title', 'Admin Dashboard - runwayhub')

@section('content')
<div class="space-y-6">
    
    <!-- Admin Header -->
    <div class="bg-gradient-to-r from-indigo-900 to-purple-900 rounded-xl p-8 shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white flex items-center gap-3">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Admin Dashboard
                </h1>
                <p class="text-indigo-200 mt-2">Finance Reports & Revenue Analytics</p>
            </div>
            
            <!-- Quick Stats -->
            <div class="flex items-center gap-6">
                <div class="text-center">
                    <p class="text-indigo-200 text-sm">Gesamt-Revenue</p>
                    <p class="text-3xl font-bold text-white" x-data="{ revenue: 0 }">
                        <span x-text="revenue.toLocaleString('de-DE', { style: 'currency', currency: 'EUR' })"></span>
                    </p>
                </div>
                
                <div class="text-center">
                    <p class="text-indigo-200 text-sm">Flight Count</p>
                    <p class="text-3xl font-bold text-white" x-data="{ count: 0 }">
                        <span x-text="count"></span>
                    </p>
                </div>
                
                <div class="text-center">
                    <p class="text-indigo-200 text-sm">Active Pilots</p>
                    <p class="text-3xl font-bold text-white" x-data="{ pilots: 0 }">
                        <span x-text="pilots"></span>
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Revenue vs Cost Chart -->
    <div class="bg-slate-800 rounded-xl p-6 shadow-lg">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-white flex items-center gap-3">
                <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                </svg>
                Revenue vs Cost Analysis
            </h2>
            
            <div class="flex items-center gap-3">
                <select class="bg-slate-700 border border-slate-600 text-white px-3 py-1.5 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                    <option value="monthly">Monatlich</option>
                    <option value="yearly">Jährlich</option>
                    <option value="all-time">Gesamtzeitraum</option>
                </select>
                
                <button class="bg-slate-700 hover:bg-slate-600 text-white px-3 py-1.5 rounded-lg text-sm transition-colors">
                    Export CSV
                </button>
            </div>
        </div>
        
        <!-- Chart Canvas -->
        <div class="bg-slate-700/50 rounded-lg p-4 h-96 flex items-center justify-center">
            <canvas id="revenueChart"></canvas>
            
            <!-- Loading State -->
            <template x-if="loading">
                <div class="text-center py-12">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary mx-auto mb-4"></div>
                    <p class="text-slate-400">Lade Finanzdaten...</p>
                </div>
            </template>
            
            <!-- Empty State -->
            <template x-if="!loading && revenueData.length === 0">
                <div class="text-center py-12 bg-slate-700/30 rounded-lg">
                    <svg class="w-16 h-16 text-slate-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <p class="text-slate-400 mb-2">Keine Finanzdaten verfügbar</p>
                    <p class="text-sm text-slate-500">Warte auf erste Flüge und Revenue-Erträge</p>
                </div>
            </template>
        </div>
        
        <!-- Chart Legend -->
        <div class="flex items-center justify-center gap-6 mt-4">
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-green-500"></span>
                <span class="text-slate-300 text-sm">Revenue</span>
            </div>
            
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-red-500"></span>
                <span class="text-slate-300 text-sm">Kosten</span>
            </div>
            
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                <span class="text-slate-300 text-sm">Profit</span>
            </div>
        </div>
    </div>
    
    <!-- Break-even Analysis -->
    <div class="bg-gradient-to-r from-green-900/80 to-emerald-900/80 rounded-xl p-6 shadow-lg">
        <h2 class="text-xl font-bold text-white mb-4 flex items-center gap-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
            </svg>
            Break-even Analyse (LBA §15 Compliance)
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Metric Card -->
            <div class="bg-slate-800/50 rounded-lg p-4">
                <p class="text-slate-400 text-sm mb-1">Durchschnittliche Flugkosten</p>
                <p class="text-2xl font-bold text-white" x-data="{ cost: 0 }">
                    <span x-text="cost.toLocaleString('de-DE', { style: 'currency', currency: 'EUR' })"></span>
                </p>
            </div>
            
            <div class="bg-slate-800/50 rounded-lg p-4">
                <p class="text-slate-400 text-sm mb-1">Durchschnittliche Einnahmen</p>
                <p class="text-2xl font-bold text-green-400" x-data="{ revenue: 0 }">
                    <span x-text="revenue.toLocaleString('de-DE', { style: 'currency', currency: 'EUR' })"></span>
                </p>
            </div>
            
            <div class="bg-slate-800/50 rounded-lg p-4">
                <p class="text-slate-400 text-sm mb-1">Profit Margin</p>
                <p class="text-2xl font-bold text-accent" x-data="{ margin: 0 }">
                    <span x-text="margin + '%'"></span>
                </p>
            </div>
        </div>
    </div>
    
    <!-- Flight Performance Stats -->
    <div class="bg-slate-800 rounded-xl p-6 shadow-lg">
        <h2 class="text-xl font-bold text-white mb-4 flex items-center gap-3">
            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            Flugperformance Statistik
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Stat Card -->
            <template x-for="stat in performanceStats" :key="stat.label">
                <div class="bg-slate-700/50 rounded-lg p-4 text-center">
                    <p class="text-slate-400 text-xs mb-1" x-text="stat.label"></p>
                    <p class="text-2xl font-bold text-white" x-text="stat.value"></p>
                    <p class="text-sm text-slate-500 mt-1" x-text="stat.unit"></p>
                </div>
            </template>
        </div>
    </div>
    
    <!-- Recent Activity Feed -->
    <div class="bg-slate-800 rounded-xl p-6 shadow-lg">
        <h2 class="text-xl font-bold text-white mb-4 flex items-center gap-3">
            <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Letzte Aktivitäten
        </h2>
        
        <div class="space-y-3">
            <!-- Activity Item -->
            <template x-for="activity in activities" :key="activity.id">
                <div class="flex items-start gap-3 bg-slate-700/30 rounded-lg p-4 hover:bg-slate-700/50 transition-colors">
                    <!-- Icon -->
                    <svg 
                        :class="{
                            'text-green-400': activity.type === 'completed',
                            'text-blue-400': activity.type === 'booking',
                            'text-yellow-400': activity.type === 'warning'
                        }"
                        class="w-5 h-5 flex-shrink-0 mt-0.5"
                        fill="none" 
                        stroke="currentColor" 
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    
                    <!-- Content -->
                    <div class="flex-1">
                        <p class="text-slate-200 text-sm" x-text="activity.message"></p>
                        <p class="text-slate-400 text-xs mt-1" x-text="formatDate(activity.timestamp)"></p>
                    </div>
                    
                    <!-- Type Badge -->
                    <span 
                        :class="{
                            'bg-green-500/20 text-green-300': activity.type === 'completed',
                            'bg-blue-500/20 text-blue-300': activity.type === 'booking',
                            'bg-yellow-500/20 text-yellow-300': activity.type === 'warning'
                        }"
                        class="px-2 py-1 rounded-full text-xs capitalize">
                        <span x-text="activity.type"></span>
                    </span>
                </div>
            </template>
        </div>
    </div>
    
</div>

@push('scripts')
<script>
// Chart.js Revenue vs Cost Chart
const ctx = document.getElementById('revenueChart').getContext('2d');

const revenueData = {
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
    datasets: [
        {
            label: 'Revenue',
            data: [12000, 15000, 18000, 22000, 25000, 28000],
            borderColor: '#10B981',
            backgroundColor: 'rgba(16, 185, 129, 0.1)',
            tension: 0.4,
            fill: true
        },
        {
            label: 'Kosten',
            data: [8000, 9500, 11000, 13000, 15000, 17000],
            borderColor: '#EF4444',
            backgroundColor: 'rgba(239, 68, 68, 0.1)',
            tension: 0.4,
            fill: true
        },
        {
            label: 'Profit',
            data: [4000, 5500, 7000, 9000, 10000, 11000],
            borderColor: '#3B82F6',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.4,
            fill: true
        }
    ]
};

const revenueChart = new Chart(ctx, {
    type: 'line',
    data: revenueData,
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                labels: { color: '#94A3B8' }
            },
            tooltip: {
                mode: 'index',
                intersect: false
            }
        },
        scales: {
            x: {
                ticks: { color: '#64748B' },
                grid: { color: '#334155' }
            },
            y: {
                beginAtZero: true,
                ticks: { 
                    color: '#64748B',
                    callback: function(value) {
                        return '€' + value / 1000 + 'k';
                    }
                },
                grid: { color: '#334155' }
            }
        }
    }
});

// Alpine.js Data Binding
Alpine.data('admin', () => ({
    loading: true,
    
    init() {
        this.loadFinancialData();
    },
    
    loadFinancialData() {
        // Simulated data - replace with API call
        setTimeout(() => {
            this.revenue = 28000;
            this.cost = 17000;
            this.margin = 39.2;
            this.pilots = 45;
            this.count = 128;
            
            // Performance Stats
            this.performanceStats = [
                { label: 'On-Time %', value: '94.5%', unit: '%' },
                { label: 'Avg Rating', value: '4.8', unit: '⭐' },
                { label: 'Completed', value: '128', unit: 'Flüge' },
                { label: 'Pending', value: '23', unit: 'Flüge' }
            ];
            
            // Activities
            this.activities = [
                { id: 1, type: 'completed', message: 'Flight RWH-045 completed by Pilot ALPHA 1', timestamp: new Date() },
                { id: 2, type: 'booking', message: 'New booking for Flight RWH-046', timestamp: new Date() },
                { id: 3, type: 'warning', message: 'Maintenance scheduled for Aircraft D-AIMR', timestamp: new Date() }
            ];
            
            this.loading = false;
        }, 1000);
    },
    
    formatDate(date) {
        return date.toLocaleString('de-DE', {
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
