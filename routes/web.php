<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
Route::middleware(['auth', 'session.expired'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::get('/attendance',[StudentController::class,'attendance'])->name('attendance');
    Route::get('/trainingphotos',[StudentController::class,'trainingphotos'])->name('trainingphotos');
    Route::get('/trainingvideos',[StudentController::class,'trainingvideos'])->name('trainingvideos');
    Route::get('/trainingcompcertificate',[StudentController::class,'trainingcompcertificate'])->name('trainingcompcertificate');
    
    Route::get('/feedback',[StudentController::class,'writtenfeedback'])->name('writtenfeedback');
    Route::get('/uploadfeedback',[StudentController::class,'uploadfeedback'])->name('uploadfeedback');
    Route::get('/uploadmedia',[StudentController::class,'uploadmedia'])->name('uploadmedia');

    Route::get('/uploadreport',[ReportController::class,'uploadreport'])->name('uploadreport');

    Route::get('/uploadbills',[BillController::class,'uploadbills'])->name('uploadbills');

    Route::view('/trainerlist','trainerlist')->name('trainerlist');

    Route::get('/get-dlcs/{district_id}', [RegisteredUserController::class, 'getDlcs']);


    Route::get('/schools', [SchoolController::class, 'index'])->name('index');
    Route::get('/schools/get-by-dlc/{dlc_id}', [SchoolController::class, 'getByDlc'])->name('schools.getByDlc');
});
// Route::get('/filter', [RegisteredUserController::class, 'index'])->name('index');
// Route::get('/filter/blocks/{dlc_id}', [RegisteredUserController::class, 'getBlocks'])->name('blocks');
// Route::get('/filter/schools/{block_id}', [RegisteredUserController::class, 'getSchools'])->name('schools');

require __DIR__.'/auth.php';
