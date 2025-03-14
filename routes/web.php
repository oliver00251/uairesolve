<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ComentarioController;
use App\Http\Controllers\OcorrenciaController;
use Illuminate\Support\Facades\Route;

/*
|-------------------------------------------------------------------------- 
| Web Routes 
|-------------------------------------------------------------------------- 
| 
| Here is where you can register web routes for your application. These 
| routes are loaded by the RouteServiceProvider within a group which 
| contains the "web" middleware group. Now create something great! 
|
*/

Route::get('/', function () {
    return view('welcome');
});

# Ocorrencias
Route::resource('ocorrencias', OcorrenciaController::class)->except(['show']);  // Excluímos a rota 'show' da resource para evitar conflito.
Route::get('/ocorrencias/{filtro?}', [OcorrenciaController::class, 'index'])->name('ocorrencias.index');  // Listagem de ocorrências
Route::get('/ocorrencias/show/{ocorrencia}', [OcorrenciaController::class, 'show'])->name('ocorrencias.show');  // Detalhes da ocorrência
Route::get('/ocorrencias/create/{tipo?}', [OcorrenciaController::class, 'create'])->name('ocorrencias.create');  // Criação de nova ocorrência

Route::post('ocorrencias/{ocorrencia}/comentarios', [ComentarioController::class, 'store'])->name('comentarios.store');  // Rota para criação de comentários

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
});

# Login e Registro
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
