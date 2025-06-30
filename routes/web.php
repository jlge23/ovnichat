<?php

use App\Events\TestEvent;
use App\Http\Controllers\CanaleController;
use App\Http\Controllers\ComboController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render("Home");
});

Route::get('/webhook/whatsapp', [WebhookController::class, 'verify']);
Route::post('/webhook/whatsapp', [WebhookController::class, 'handleWhatsapp']);

Route::get('/webhook/messenger', [WebhookController::class, 'verify']);
Route::post('/webhook/messenger', [WebhookController::class, 'handleMessenger']);

Route::get('/combos', [ComboController::class, 'index'])->name('combos.index');
Route::post('/combos', [ComboController::class, 'store'])->name('combos.store');
Route::get('/combos/{id}/edit', [ComboController::class, 'edit'])->name('combos.edit');
Route::put('/combos/{combo}', [ComboController::class, 'update'])->name('combos.update');
Route::delete('/combos/{combo}', [ComboController::class, 'destroy'])->name('combos.destroy');


Route::get('/productos', [ProductoController::class, 'index'])->name('productos.index');
Route::get('/productos/{id}', [ProductoController::class, 'show'])->name('productos.show');

Route::get('/canal', [CanaleController::class, 'index']);

Route::get('/test', function () {
    event(new TestEvent('Hola Mundo'));
    broadcast(new TestEvent('Hola Mundo'));
    TestEvent::dispatch('Hola Mundo');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
