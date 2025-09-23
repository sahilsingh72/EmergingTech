<?php

use App\Services\OneDriveService;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceUploadController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\CoordinatorController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\OneDriveController;
use App\Http\Controllers\TrainingEvidenceController;
use App\Http\Controllers\TrainingUploadController;
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
    
    Route::get('/attendance',[AttendanceController::class,'attendance'])->name('attendance');
    Route::post('/attendance', [AttendanceController::class, 'upload'])->name('upload.attendance');

    Route::get('/trainingphotos',[TrainingEvidenceController::class,'trainingphotos'])->name('trainingphotos');
    Route::post('/trainingphotos',[TrainingEvidenceController::class,'upload'])->name('upload.trainingphotos');
    
    Route::get('/trainingvideos',[TrainingEvidenceController::class,'trainingvideos'])->name('trainingvideos');
    Route::post('/trainingvideos',[TrainingEvidenceController::class,'uploadvideo'])->name('upload.trainingvideos');

    Route::get('/trainingcompcertificate',[TrainingEvidenceController::class,'trainingcompcertificate'])->name('trainingcompcertificate');
    Route::post('/trainingcompcertificate',[TrainingEvidenceController::class,'uploadCcertificate'])->name('upload.certificate');
    Route::get('/addstudent',[StudentController::class,'addstudent'])->name('addstudent');
    // Route::view('/addstudentsin','addstudentsin')->name('addstudentsin');
    
    Route::get('/addstudentsep', [StudentController::class, 'addstudentsin'])->name('single.addstudent');
    Route::post('/addstudentsep', [StudentController::class, 'store'])->name('student.store');

    Route::post('/students/import', [StudentController::class, 'import'])->name('students.import');
    Route::get('/student',[StudentController::class,'studentlist'])->name('studentlist');
    
    // View student details
    Route::get('/students/{id}', [StudentController::class, 'show'])->name('student.view');

    // Edit student
    Route::get('/students/{id}/edit', [StudentController::class, 'edit'])->name('student.edit');
    Route::put('/students/{id}', [StudentController::class, 'update'])->name('student.update');

    // Delete student
    Route::delete('/students/{id}', [StudentController::class, 'destroy'])->name('student.delete');
    
    Route::get('/feedback',[FeedbackController::class,'writtenfeedback'])->name('writtenfeedback');
    Route::post('/feedback',[FeedbackController::class,'uploadwrittenfeedback'])->name('upload.writtenfeedback');
    Route::get('/uploadfeedback',[FeedbackController::class,'uploadfeedback'])->name('uploadfeedback');
    Route::post('/uploadfeedback',[FeedbackController::class,'uploadvideofeedback'])->name('upload.videofeedback');
    Route::get('/onlinefeedback',[FeedbackController::class,'onlinefeedback'])->name('onlinefeedback');

    Route::get('/uploadreport',[ReportController::class,'uploadreport'])->name('uploadreport');

    Route::get('/uploadbills',[BillController::class,'uploadbills'])->name('uploadbills');
    Route::get('/uploadtravelbills',[BillController::class,'uploadtravelbills'])->name('uploadtravelbills');
    Route::get('/uploadexpensebills',[BillController::class,'uploadexpensebills'])->name('uploadexpensebills');
    
    Route::get('/trainerlist', [TrainerController::class, 'index'])->name('trainers.index');
    Route::post('/trainers/store', [TrainerController::class, 'store'])->name('trainers.store');
    Route::get('/trainers/{trainer}/edit', [TrainerController::class, 'edit'])->name('trainers.edit');   
    Route::put('/trainers/{trainer}', [TrainerController::class, 'update'])->name('trainers.update'); 
    Route::delete('/trainers/{trainer}', [TrainerController::class, 'destroy'])->name('trainers.destroy');

    Route::get('/coordinatorlist', [CoordinatorController::class, 'index'])->name('coordinators.index');
    Route::post('/coordinators/store', [CoordinatorController::class, 'store'])->name('coordinators.store');
    Route::get('/coordinators/{coordinator}/edit', [CoordinatorController::class, 'edit'])->name('coordinators.edit');   
    Route::put('/coordinators/{coordinator}', [CoordinatorController::class, 'update'])->name('coordinators.update'); 
    Route::delete('/coordinators/{coordinator}', [CoordinatorController::class, 'destroy'])->name('coordinators.destroy');  
    


    Route::get('/schools', [SchoolController::class, 'index'])->name('index');
    Route::get('/schools/get-by-dlc/{dlc_id}', [SchoolController::class, 'getByDlc'])->name('schools.getByDlc');
});
// Route::get('/filter', [RegisteredUserController::class, 'index'])->name('index');
// Route::get('/filter/blocks/{dlc_id}', [RegisteredUserController::class, 'getBlocks'])->name('blocks');
// Route::get('/filter/schools/{block_id}', [RegisteredUserController::class, 'getSchools'])->name('schools');





Route::get('/onedrive/login', [OneDriveController::class, 'redirectToProvider'])->name('onedrive.login');
Route::get('/onedrive/callback', [OneDriveController::class, 'handleCallback'])->name('onedrive.callback');
Route::get('/onedrive/upload', [OneDriveController::class, 'showUploadForm'])->name('onedrive.upload.form');
Route::post('/onedrive/upload', [OneDriveController::class, 'uploadFile'])->name('onedrive.upload');


require __DIR__.'/auth.php';
