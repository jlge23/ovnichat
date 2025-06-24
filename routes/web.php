<?php

use App\Events\TestEvent;
use App\Http\Controllers\ApiMessengerController;
use App\Http\Controllers\ApiWhatsAppController;
use App\Http\Controllers\CanaleController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});
/* Route::get('/',[WelcomeController::class,'index'])->name('welcome');
 */
Route::get('/webhook/whatsapp',[WebhookController::class ,'verify']);
Route::post('/webhook/whatsapp', [WebhookController::class, 'handleWhatsapp']);

Route::get('/webhook/messenger', [WebhookController::class, 'verify']);
Route::post('/webhook/messenger', [WebhookController::class, 'handleMessenger']);

/*
//WhatsApp
//Route::get('/whsend',[ApiWhatsAppController::class ,'whsend'])->name('whsend');
Route::get('/whget',[ApiWhatsAppController::class ,'whget'])->name('whget');//
Route::post('/whget',[ApiWhatsAppController::class ,'postrwhget'])->name('whget');
//Messenger

Route::get('/messenger', [ApiMessengerController::class, 'verificar']);
Route::post('/messenger', [ApiMessengerController::class, 'recibirEvento']); */


Route::get('/productos', [ProductoController::class, 'index']);
Route::get('/productos/{id}', [ProductoController::class, 'show']);

Route::get('/canal', [CanaleController::class, 'index']);

Route::get('/test', function () {
    event(new TestEvent('Hola Mundo'));
    broadcast(new TestEvent('Hola Mundo'));
    TestEvent::dispatch('Hola Mundo');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


