<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BurgerController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\StatistiqueController;

// ── AUTH ────────────────────────────────────────────────────
Route::get('/',       fn() => redirect()->route('login'));
Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout',   [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ── CLIENT ──────────────────────────────────────────────────
Route::middleware(['auth', 'role:client'])->prefix('client')->name('client.')->group(function () {

    // Catalogue burgers
    Route::get('/catalogue',       [BurgerController::class,   'catalogue'])->name('catalogue');
    Route::get('/burgers/{burger}', [BurgerController::class,  'show'])->name('burgers.show');

    // Commandes
    Route::get('/commandes',            [CommandeController::class, 'indexClient'])->name('commandes.index');
    Route::get('/commandes/create',     [CommandeController::class, 'create'])->name('commandes.create');
    Route::post('/commandes',           [CommandeController::class, 'store'])->name('commandes.store');
    Route::get('/commandes/{commande}', [CommandeController::class, 'showClient'])->name('commandes.show');
});

// ── GESTIONNAIRE ─────────────────────────────────────────────
Route::middleware(['auth', 'role:gestionnaire'])->prefix('gestionnaire')->name('gestionnaire.')->group(function () {

    // Dashboard / Statistiques
    Route::get('/dashboard', [StatistiqueController::class, 'index'])->name('dashboard');

    // Burgers
    Route::get('/burgers',              [BurgerController::class, 'index'])->name('burgers.index');
    Route::get('/burgers/create',       [BurgerController::class, 'create'])->name('burgers.create');
    Route::post('/burgers',             [BurgerController::class, 'store'])->name('burgers.store');
    Route::get('/burgers/{burger}/edit',[BurgerController::class, 'edit'])->name('burgers.edit');
    Route::put('/burgers/{burger}',     [BurgerController::class, 'update'])->name('burgers.update');
    Route::patch('/burgers/{burger}/archiver', [BurgerController::class, 'archiver'])->name('burgers.archiver');
    Route::delete('/burgers/{burger}',  [BurgerController::class, 'destroy'])->name('burgers.destroy');

    // Commandes
    Route::get('/commandes',                         [CommandeController::class, 'indexGestionnaire'])->name('commandes.index');
    Route::get('/commandes/{commande}',              [CommandeController::class, 'showGestionnaire'])->name('commandes.show');
    Route::patch('/commandes/{commande}/statut',     [CommandeController::class, 'changerStatut'])->name('commandes.statut');
    Route::patch('/commandes/{commande}/annuler',    [CommandeController::class, 'annuler'])->name('commandes.annuler');

    // Paiements
    Route::get('/commandes/{commande}/paiement',     [PaiementController::class, 'create'])->name('paiements.create');
    Route::post('/commandes/{commande}/paiement',    [PaiementController::class, 'store'])->name('paiements.store');
});
