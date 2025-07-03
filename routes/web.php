<?php

use App\Events\TestEvent;
use App\Http\Controllers\CanaleController;
use App\Http\Controllers\ComboController;
use App\Http\Controllers\MensajeController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/* Route::get('/', function () {
    return view('welcome');
}); */
Route::get('/',[ WelcomeController::class,'index'])->name('welcome');

Route::get('/webhook/whatsapp',[WebhookController::class ,'verify']);
Route::post('/webhook/whatsapp', [WebhookController::class, 'handleWhatsapp']);

Route::get('/webhook/messenger', [WebhookController::class, 'verify']);
Route::post('/webhook/messenger', [WebhookController::class, 'handleMessenger']);

Route::get('/combos', [ComboController::class, 'index'])->name('combos.index');
Route::post('/combos', [ComboController::class, 'store'])->name('combos.store');
Route::get('/combos/{id}/edit', [ComboController::class, 'edit'])->name('combos.edit');
Route::put('/combos/{combo}', [ComboController::class, 'update'])->name('combos.update');
Route::delete('/combos/{combo}', [ComboController::class, 'destroy'])->name('combos.destroy');


Route::resource('productos', ProductoController::class);

Route::get('/canal', [CanaleController::class, 'index']);

Route::get('/test', function () {
    event(new TestEvent('Hola Mundo'));
    broadcast(new TestEvent('Hola Mundo'));
    TestEvent::dispatch('Hola Mundo');
});

Route::get('/empezar', [MensajeController::class, 'empezar']);
Route::get('/terminar', [MensajeController::class, 'terminar']);
Route::post('/llama', [MensajeController::class, 'llama'])->name('llama');
Route::get('/mie' ,[MensajeController::class ,'mie'])->name('mie');
Route::get('/categorias' ,[MensajeController::class ,'categorias'])->name('mcategoriasie');
Route::get('/consulta' ,[MensajeController::class ,'consulta'])->name('consulta');


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


