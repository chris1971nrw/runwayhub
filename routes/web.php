<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\FlightBookingController;
use App\Http\Controllers\UserReportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| names are also registered as nameable routes so we can access them
| from the views and controllers easily.
|
*/

// Home / Landing Page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Auth Routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/login', [Controller::class, 'login'])->name('auth.login');
Route::post('/register', [Controller::class, 'register'])->name('auth.register');

// Pilot Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('pilots.dashboard');
    })->name('pilots.dashboard');
    
    // Flights List
    Route::get('/flights', [Controller::class, 'flightsIndex'])->name('pilots.flights.index');
    Route::get('/flights/{flight}', [Controller::class, 'flightShow'])->name('pilots.flights.show');
    Route::post('/flights/{flight}/cancel', [Controller::class, 'cancelFlight'])->name('pilots.flights.cancel');
    
    // Flight Logs
    Route::get('/logs', [Controller::class, 'logsIndex'])->name('pilots.logs.index');
    Route::post('/flights/{flight}/log', [Controller::class, 'submitFlightLog'])->name('pilots.flights.submit-log');
    Route::post('/flights/{flight}/screenshots', [Controller::class, 'uploadScreenshots'])->name('pilots.flights.screenshots');
    
    // Weather Widget Component
    Route::get('/weather', [Controller::class, 'weatherWidget'])->name('pilots.weather');
});

// Ground Control Routes (Dispatch)
Route::middleware(['auth'])->group(function () {
    // Dispatch Dashboard
    Route::get('/dispatch', function () {
        return view('ground_control.schedules');
    })->name('ground_control.schedules');
    
    // Schedule Management
    Route::get('/dispatch/schedules/create', [Controller::class, 'createSchedule'])->name('ground_control.schedules.create');
    Route::post('/dispatch/schedules', [Controller::class, 'storeSchedule'])->name('ground_control.schedules.store');
    Route::get('/dispatch/schedules/{schedule}', [Controller::class, 'showSchedule'])->name('ground_control.schedules.show');
    Route::put('/dispatch/schedules/{schedule}', [Controller::class, 'updateSchedule'])->name('ground_control.schedules.update');
    Route::post('/dispatch/schedules/{schedule}/assign-pilot', [Controller::class, 'assignPilot'])->name('ground_control.schedules.assign-pilot');
    
    // Aircraft Management
    Route::get('/dispatch/aircraft', [Controller::class, 'aircraftIndex'])->name('ground_control.aircraft.index');
    Route::post('/dispatch/aircraft', [Controller::class, 'storeAircraft'])->name('ground_control.aircraft.store');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Admin Dashboard
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
    
    // Finance Reports
    Route::get('/admin/finance', [Controller::class, 'financeReport'])->name('admin.finance');
    Route::get('/admin/revenue', [Controller::class, 'revenueReport'])->name('admin.revenue');
    Route::get('/admin/costs', [Controller::class, 'costReport'])->name('admin.costs');
    
    // User Management
    Route::get('/admin/users', [Controller::class, 'usersIndex'])->name('admin.users.index');
    Route::delete('/admin/users/{user}', [Controller::class, 'deleteUser'])->name('admin.users.delete');
});

// API Routes (Future OAuth2 Integration)
Route::prefix('api/v1')->group(function () {
    // Flight Booking API
    Route::post('/flights/booking', [FlightBookingController::class, 'store'])->middleware(['auth']);
    Route::get('/flights/{flight}', [FlightBookingController::class, 'show'])->middleware(['auth']);
    Route::put('/flights/{flight}/cancel', [FlightBookingController::class, 'cancel'])->middleware(['auth']);
    
    // Weather API (Public)
    Route::get('/weather/{location}', [Controller::class, 'weatherData'])->name('api.weather');
});

// Error Handling
Route::get('/404', function () {
    return view('errors.404');
})->name('errors.404');

Route::get('/500', function () {
    return view('errors.500');
})->name('errors.500');

// GitHub Issues Integration (User Reports)
Route::post('/reports/github/issues', [UserReportController::class, 'createIssue'])->middleware(['auth']);
Route::get('/reports/github/issues', [UserReportController::class, 'listIssues'])->middleware(['auth']);
