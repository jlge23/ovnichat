<?php

use App\Events\TestEvent;
use App\Http\Controllers\CanaleController;
use App\Http\Controllers\ComboController;
use App\Http\Controllers\MensajeController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::post('/llama', [WelcomeController::class, 'llama'])->name('llama');
Route::get('/autocurar', [WelcomeController::class, 'autocurar'])->name('autocurar');
Route::post('/asignarIntent/{embedding}', [WelcomeController::class, 'asignarIntent'])->name('asignarIntent');
Route::delete('/embedding/{embedding}', [WelcomeController::class, 'destroy'])->name('destroy');


Route::get('/webhook/whatsapp', [WebhookController::class, 'verify']);
Route::post('/webhook/whatsapp', [WebhookController::class, 'handleWhatsapp']);

Route::get('/webhook/messenger', [WebhookController::class, 'verify']);
Route::post('/webhook/messenger', [WebhookController::class, 'handleMessenger']);

Route::get('/combos', [ComboController::class, 'index'])->name('combos.index');
Route::post('/combos', [ComboController::class, 'store'])->name('combos.store');
Route::get('/combos/{id}/edit', [ComboController::class, 'edit'])->name('combos.edit');
Route::put('/combos/{combo}', [ComboController::class, 'update'])->name('combos.update');
Route::delete('/combos/{combo}', [ComboController::class, 'destroy'])->name('combos.destroy');


// Route::resource('productos', ProductoController::class);

Route::get('/canal', [CanaleController::class, 'index']);

/* Route::get('/test', function () {
    event(new TestEvent('Hola Mundo'));
    broadcast(new TestEvent('Hola Mundo'));
    TestEvent::dispatch('Hola Mundo');
}); */


Route::get('/mie', [MensajeController::class, 'mie'])->name('mie');
Route::get('/categorias', [MensajeController::class, 'categorias'])->name('categorias');
Route::get('/consulta', [MensajeController::class, 'consulta'])->name('consulta');
Route::get('/llm', [MensajeController::class, 'LLM'])->name('llm');
Route::get('/system', [MensajeController::class, 'construirSystemPrompt'])->name('system');


Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/test', [TestController::class, 'index'])->name('test');
Route::post('/testia', [TestController::class, 'testia'])->name('testia');

Route::get('/chatbot', function () {
    return view('ChatbotBuilder.index');
})->name('chatbot');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/chatbot', function () {
    return view('ChatbotBuilder.index');
});

Route::get('/probar-php', function () {
    return phpinfo();
});

Route::get('/login', fn() => Inertia::render("Login"))->name("login");

Route::middleware("auth")->group(function () {
    Route::get("/dashboard", fn() => Inertia::render("Dashboard"));

    Route::resource('productos', ProductoController::class)->names([
        'index' => 'productos.index',
        'create' => 'productos.create',
        'update' => 'productos.update'
    ]);
});
