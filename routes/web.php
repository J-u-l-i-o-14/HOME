<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\BloodBagController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\TransfusionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StockThresholdController;

// Page d'accueil
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/prendre-rendez-vous', [\App\Http\Controllers\AppointmentController::class, 'publicForm'])->name('appointment.public.form');
Route::post('/prendre-rendez-vous', [\App\Http\Controllers\AppointmentController::class, 'publicStore'])->name('appointment.public.store');

// Dashboard principal avec redirection selon le rôle
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Routes pour les donneurs (pas de dashboard)
Route::middleware(['auth', 'role:donor'])->group(function () {
    Route::resource('appointments', AppointmentController::class)->except(['destroy']);
    Route::patch('/appointments/{appointment}/cancel', [AppointmentController::class, 'destroy'])->name('appointments.cancel');
});

// Routes pour les patients (pas de dashboard)
Route::middleware(['auth', 'role:patient'])->group(function () {
    // Routes spécifiques aux patients si nécessaire
});

// Routes pour les managers (avec dashboard)
Route::middleware(['auth', 'role:manager'])->group(function () {
    // Route::resource('campaigns', CampaignController::class); // doublon supprimé
    Route::post('campaigns/{campaign}/publish', [CampaignController::class, 'publish'])->name('campaigns.publish');
    Route::post('campaigns/{campaign}/archive', [CampaignController::class, 'archive'])->name('campaigns.archive');
    Route::resource('appointments', AppointmentController::class);
    Route::patch('/appointments/{appointment}/confirm', [AppointmentController::class, 'confirm'])->name('appointments.confirm');
    Route::get('/blood-bags/stock', [BloodBagController::class, 'stock'])->name('blood-bags.stock');
});

// CRUD Donors
Route::resource('donors', App\Http\Controllers\DonorController::class);

// Routes pour les clients (avec dashboard)
Route::middleware(['auth', 'role:client'])->group(function () {
    Route::get('/campaigns/public', [CampaignController::class, 'upcomingPublic'])->name('campaigns.public');
    Route::get('/blood-bags/available', [BloodBagController::class, 'available'])->name('blood-bags.available');
});

// Routes pour les admins (avec dashboard)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('blood-bags', BloodBagController::class);
    Route::resource('campaigns', CampaignController::class);
    Route::post('campaigns/{campaign}/publish', [CampaignController::class, 'publish'])->name('campaigns.publish');
    Route::post('campaigns/{campaign}/archive', [CampaignController::class, 'archive'])->name('campaigns.archive');
    Route::resource('donations', DonationController::class);
    Route::resource('patients', PatientController::class);
    Route::resource('transfusions', TransfusionController::class);
    Route::resource('centers', \App\Http\Controllers\CenterController::class);
    // Routes spéciales pour les rendez-vous (admin peut tout voir/modifier)
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.admin.index');
    Route::patch('/appointments/{appointment}/confirm', [AppointmentController::class, 'confirm'])->name('appointments.confirm');
    // Campagnes à venir (public pour les donneurs)

});

// Routes publiques pour les campagnes (visible par tous les utilisateurs connectés)
Route::middleware('auth')->group(function () {
    Route::get('/campaigns/public', [CampaignController::class, 'upcomingPublic'])->name('campaigns.public');
});


Route::post('/prendre-rendez-vous', [AppointmentController::class, 'publicStore'])->name('appointment.public.store');

Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');
Route::post('/reservation', [\App\Http\Controllers\SearchBloodController::class, 'storeReservation'])->name('reservation.store');

// Routes pour la gestion des stocks (admin + manager)
Route::middleware(['auth', 'role:admin,manager'])->group(function () {
    Route::get('/blood-bags/stock', [BloodBagController::class, 'stock'])->name('blood-bags.stock');
    Route::post('/blood-bags/mark-expired', [BloodBagController::class, 'markExpired'])->name('blood-bags.markExpired');
});

// Recherche de sang par région et groupe sanguin
Route::get('/recherche-sang', [\App\Http\Controllers\SearchBloodController::class, 'search'])->name('search.blood');

// Recherche AJAX de sang (API)
Route::post('/api/recherche-sang', [\App\Http\Controllers\SearchBloodController::class, 'searchAjax'])->name('api.search.blood');

// API: centres par région
Route::get('/api/centers-by-region/{region}', [\App\Http\Controllers\SearchBloodController::class, 'centersByRegion']);

// Patients, donneurs, transfusions
Route::middleware(['auth', 'role:admin,manager'])->group(function () {
    Route::resource('donors', \App\Http\Controllers\DonationController::class);
    Route::get('/regions', [\App\Http\Controllers\RegionController::class, 'index'])->name('regions.index');
    Route::get('/regions/create', [\App\Http\Controllers\RegionController::class, 'create'])->name('regions.create');
    Route::post('/regions', [\App\Http\Controllers\RegionController::class, 'store'])->name('regions.store');

// Patients et transfusions
    Route::resource('patients', \App\Http\Controllers\PatientController::class);
    Route::resource('transfusions', \App\Http\Controllers\TransfusionController::class);

// Export financier
    Route::get('/payments/export/csv', [\App\Http\Controllers\PaymentExportController::class, 'exportCsv'])->name('payments.export.csv');
    Route::get('/payments/export/xlsx', [\App\Http\Controllers\PaymentExportController::class, 'exportXlsx'])->name('payments.export.xlsx');
});

// Marquer une notification comme lue
Route::post('/notifications/{notification}/read', function (\App\Models\Notification $notification) {
    $notification->update(['read' => true]);
    return back();
})->name('notifications.read')->middleware('auth');

// Route de test temporaire pour vérifier les rôles
Route::get('/test-roles', function () {
    $users = \App\Models\User::all();
    $roles = [];
    foreach ($users as $user) {
        $roles[] = [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'has_dashboard' => $user->has_dashboard,
        ];
    }
    return response()->json($roles);
})->name('test.roles');

require __DIR__.'/auth.php';
