@extends('layouts.app')

@section('title', 'Login - runwayhub')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-900">
    
    <!-- Login Card -->
    <div class="bg-slate-800 rounded-2xl p-8 shadow-2xl w-full max-w-md mx-4">
        
        <!-- Logo & Title -->
        <div class="text-center mb-8">
            <svg class="w-16 h-16 text-primary mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 002 2 2 2 0 012 2v2.945M15 2.935V5.5A2.5 2.5 0 0017.5 8h.5a2 2 0 012 2 2 2 0 002 2 2 2 0 012 2v2.945M18 9.935V11a2 2 0 01-2 2h-1a2 2 0 00-2 2 2 2 0 01-2 2v2.945"/>
            </svg>
            
            <h1 class="text-3xl font-bold text-white mb-2">🌉 runwayhub</h1>
            <p class="text-slate-400">Login zu deiner Virtual Airline Community</p>
        </div>
        
        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            
            <!-- Email Input -->
            <div>
                <label for="email" class="block text-sm font-medium text-slate-300 mb-2">E-Mail Adresse</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        value="{{ old('email') }}" 
                        required 
                        autofocus 
                        class="w-full bg-slate-700 border border-slate-600 text-white pl-10 pr-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary placeholder-slate-400"
                        placeholder="deine@email.com">
                </div>
            </div>
            
            <!-- Password Input -->
            <div>
                <label for="password" class="block text-sm font-medium text-slate-300 mb-2">Passwort</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        required 
                        class="w-full bg-slate-700 border border-slate-600 text-white pl-10 pr-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary placeholder-slate-400">
                    <button type="button" @click="togglePasswordVisibility()" 
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-white transition-colors">
                        <svg x-show="passwordVisible" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.978a9 9 0 11-.892 8.836m0-10.5c0-4.478 3.522-8 8-8s8 3.522 8 8a9 9 0 01-6.928 8.836m-3.38-2.478A9 9 0 0112 19c-4.478 0-8-3.522-8-8"/>
                        </svg>
                        <svg x-show="!passwordVisible" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.543 7-1.274 4.057-5.064 7-9.543 7-4.477 0-8.268-2.943-9.543-7z"/>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Remember & Forgot Password -->
            <div class="flex items-center justify-between">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="rounded bg-slate-700 border-slate-600 text-primary focus:ring-primary">
                    <span class="ml-2 text-sm text-slate-400">Angemeldet bleiben</span>
                </label>
                
                <a href="{{ route('password.request') }}" class="text-sm text-primary hover:text-blue-400 transition-colors">
                    Passwort vergessen?
                </a>
            </div>
            
            <!-- Submit Button -->
            <button type="submit" 
                    class="w-full bg-primary hover:bg-blue-600 text-white font-medium py-3 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-slate-800">
                Anmelden
            </button>
        </form>
        
        <!-- Divider -->
        <div class="relative my-8">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-slate-700"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-slate-800 text-slate-400">oder</span>
            </div>
        </div>
        
        <!-- Social Login -->
        <div class="grid grid-cols-2 gap-4">
            <button class="flex items-center justify-center bg-slate-700 hover:bg-slate-600 text-white py-3 rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12.545,10.239v3.821h5.445c-0.712,2.315-2.647,3.172-3.259,3.28c-0.595,0.105-1.378,0.142-1.995,0.142-0.622,0-1.471-0.052-2.172-0.162V10.239H12.545z M12.545,9.556h3.892c0.963,0 1.898-0.085 2.763-0.259V4.566C16.712 4.214 15.144 4 13.467 4H8.786c-1.683 0-3.247 0.214-4.529 0.566V9.297C5.492 9.46 7.149 9.556 8.786 9.556h3.759z M6.316 13.439c-0.748 0-1.411-0.266-1.891-0.71l-0.001-0.001c-0.48-0.444-0.748-1.093-0.748-1.754V4.566C5.767 4.214 6.913 4 8.786 4h4.681c1.873 0 3.019 0.214 3.672 0.566v4.12C17.119 9.093 16.456 9.359 15.708 9.803c-0.48 0.444-1.143 0.71-1.891 0.71H6.316z"/>
                </svg>
                Mit Google fortfahren
            </button>
            
            <button class="flex items-center justify-center bg-slate-700 hover:bg-slate-600 text-white py-3 rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.605.069-.605 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.399-1.988 1.029-2.678-.103-.253-.446-1.272.098-2.647 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.375.202 2.394.1 2.647.64.69 1.028 1.585 1.028 2.678 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"/>
                </svg>
                Mit GitHub fortfahren
            </button>
        </div>
        
        <!-- Register Link -->
        <p class="mt-8 text-center text-sm text-slate-400">
            Noch kein Account? 
            <a href="{{ route('register') }}" class="text-primary hover:text-blue-400 font-medium transition-colors">
                Registriere dich jetzt
            </a>
        </p>
    </div>
    
    <!-- Footer -->
    <p class="text-center text-slate-500 text-sm mt-8">
        &copy; {{ date('Y') }} runwayhub. Alle Rechte vorbehalten.
    </p>
    
</div>

@push('scripts')
<script>
// Toggle password visibility
let passwordVisible = false;

function togglePasswordVisibility() {
    passwordVisible = !passwordVisible;
    const input = document.getElementById('password');
    input.type = passwordVisible ? 'text' : 'password';
}
</script>
@endpush
@endsection
