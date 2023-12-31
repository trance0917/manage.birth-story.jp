<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('index');
    });

    Route::get('patients', [PatientsController::class, 'index'])->name('patients-index');


    Route::get('patients/json', [PatientsController::class, 'json'])->name('patients-json');

    Route::get('patients/{tbl_patient}', [PatientsController::class, 'edit'])->name('patients-edit');
//    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/github/webhook/', [\App\Http\Controllers\WebHookController::class,'github'])->name('github-webhook');

require __DIR__.'/auth.php';
