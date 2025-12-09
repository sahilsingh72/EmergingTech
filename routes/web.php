<?php

use App\Services\OneDriveService;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\CoordinatorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\OneDriveController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\TrainingEvidenceController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/district-progress', [DashboardController::class, 'getDistrictProgress'])->name('district.progress');
    
    Route::get('/chart-data', [DashboardController::class, 'getChartData'])->name('chart.data');

});


Route::post('/uploadgallery', [DashboardController::class, 'photogallery'])->name('uploadgallery');

Route::middleware(['auth', 'session.expired'])->group(function () {

    Route::get('/calendar-events', [DashboardController::class, 'calendarEvents']);
    Route::post('/update-training-date', [DashboardController::class, 'updateTrainingDate']);

    Route::get('/gallery', [GalleryController::class, 'gallery'])->name('gallery');
    Route::get('/get-schools-by-district-{districtId}', [GalleryController::class, 'getSchoolsByDistrict']);
    Route::get('/preview-file', [GalleryController::class, 'previewFile']);
    Route::get('/preview-video', [GalleryController::class, 'previewVideo'])->name('preview.video');
    Route::get('/download-file', [GalleryController::class, 'downloadFile'])->name('download.file');

    Route::get('/fetchgallery', [DashboardController::class, 'fetchGallery'])->name('fetch.gallery');
    Route::get('/preview-image', [DashboardController::class, 'previewImage'])->name('preview.image');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::get('/student-attendance',[AttendanceController::class,'studentAttendance'])->name('student.attendance.sheet');
    Route::post('/attendance/save-all', [AttendanceController::class, 'saveAll'])->name('attendance.saveAll');
    
    Route::get('/attendance',[AttendanceController::class,'attendance'])->name('attendance');
    Route::post('/attendance', [AttendanceController::class, 'upload'])->name('upload.attendance');
    Route::get('/attendance-list',[AttendanceController::class,'attendanceList'])->name('attendance.list');
    Route::get('/attendance-list/{id}/edit', [AttendanceController::class, 'edit'])->name('attendance.list.edit');
    Route::put('/attendance-list/{id}', [AttendanceController::class, 'update'])->name('attendance.list.update');
    Route::get('/preview-file', [AttendanceController::class, 'previewFile'])->name('preview.file');
    
    
    Route::get('/trainingphotos',[TrainingEvidenceController::class,'trainingphotos'])->name('trainingphotos');
    Route::post('/trainingphotos',[TrainingEvidenceController::class,'upload'])->name('upload.trainingphotos');
    Route::get('/trainingphotos-list',[TrainingEvidenceController::class,'trainingphotoslist'])->name('trainingphotos.list');
    Route::get('/trainingphotos-list/{id}/edit', [TrainingEvidenceController::class, 'editTrainingPhoto'])->name('training.photo.edit');
    Route::put('/trainingphotos-list/{id}', [TrainingEvidenceController::class, 'updateTrainingPhoto'])->name('training.photo.update');
    Route::get('/preview-image', [TrainingEvidenceController::class, 'previewImage'])->name('preview.image');
    
    
    Route::get('/trainingvideos',[TrainingEvidenceController::class,'trainingvideos'])->name('trainingvideos');
    Route::post('/trainingvideos',[TrainingEvidenceController::class,'uploadvideo'])->name('upload.trainingvideos');
    Route::get('/trainingvideos-list',[TrainingEvidenceController::class,'trainingvideoslist'])->name('trainingvideos.list');
    Route::get('/trainingvideos-list/{id}/edit', [TrainingEvidenceController::class, 'editTrainingVideo'])->name('training.video.edit');
    Route::put('/trainingvideos-list/{id}', [TrainingEvidenceController::class, 'updateTrainingVideo'])->name('training.video.update');
    Route::get('/preview-video', [TrainingEvidenceController::class, 'previewVideo'])->name('preview.video');


    Route::get('/trainingcompcertificate',[TrainingEvidenceController::class,'trainingcompcertificate'])->name('trainingcompcertificate');
    Route::post('/trainingcompcertificate',[TrainingEvidenceController::class,'uploadcertificate'])->name('upload.certificate');
    Route::get('/uploaded-certificates', [TrainingEvidenceController::class, 'viewUploadedCertificates'])->name('uploaded.certificates');

    Route::get('/addstudent',[StudentController::class,'addstudent'])->name('addstudent');
    // Route::view('/addstudentsin','addstudentsin')->name('addstudentsin');
    
    Route::get('/addstudentsep', [StudentController::class, 'addstudentsin'])->name('single.addstudent');
    Route::post('/addstudentsep', [StudentController::class, 'store'])->name('student.store');

    Route::post('/students/import', [StudentController::class, 'import'])->name('students.import');
    Route::get('/student',[StudentController::class,'studentlist'])->name('studentlist');
    
    // View student details
    Route::get('/students/{id}', [StudentController::class, 'show'])->name('student.view');
    
    // Route::get('/students/by-school/{schoolId}', [StudentController::class, 'getStudentsBySchool'])->name('students.bySchool');
    // Edit student
    Route::get('/students/{id}/edit', [StudentController::class, 'edit'])->name('student.edit');
    Route::put('/students/{id}', [StudentController::class, 'update'])->name('student.update');
    // Delete student
    Route::delete('/students/{id}', [StudentController::class, 'destroy'])->name('student.delete');
    
    Route::get('/student-feedback',[StudentController::class,'studentFeedback'])->name('student.feedback');
    Route::post('/student-feedback', [StudentController::class, 'uploadFeedback'])->name('student.feedback.upload');
    Route::get('/student-feedback-preview', [StudentController::class, 'previewFile'])->name('student.feedback.preview');
    Route::get('/student-feedback-{stu_id}-entry', [StudentController::class, 'createEntry'])->name('student.feedback.entryPage');
    Route::post('/student-feedback-store', [StudentController::class, 'storeStudentFeedback'])->name('student.feedback.store');
    Route::post('/student-feedback-update', [StudentController::class, 'updateFeedback'])->name('student.feedback.update');
    Route::get('/feedback-report', [StudentController::class, 'index'])->name('feedback.report');

    Route::get('/get-schools-by-district-{districtId}', [StudentController::class, 'getSchoolsByDistrict']);

    Route::get('/feedback',[FeedbackController::class,'writtenfeedback'])->name('writtenfeedback');
    Route::post('/feedback',[FeedbackController::class,'uploadwrittenfeedback'])->name('upload.writtenfeedback');

    Route::get('/uploadfeedback',[FeedbackController::class,'videofeedback'])->name('uploadfeedback');
    Route::post('/uploadfeedback',[FeedbackController::class,'uploadvideofeedback'])->name('upload.videofeedback');
    Route::get('/uploadfeedback-list',[FeedbackController::class,'videofeedbacklist'])->name('videofeedback.list');
    Route::get('/uploadfeedback-list/{id}/edit', [FeedbackController::class, 'editFeedbackVideo'])->name('feedback.video.edit');
    Route::put('/uploadfeedback-list/{id}', [FeedbackController::class, 'updatevideofeedback'])->name('feedback.video.update');

    Route::get('/uploadreport',[ReportController::class,'uploadreport'])->name('uploadreport');

    Route::get('/uploadstaffexpense',[BillController::class,'staffexpense'])->name('uploadbills');
    Route::get('/get-staff/{schoolId}/{roleId}', [BillController::class, 'getStaff']);
    Route::post('/staff-bill/store', [BillController::class, 'store'])->name('staff-bill.store');
    
    Route::get('/trainertravels',[BillController::class,'trainerTravels'])->name('trainer.travels');    
    Route::get('/get-trainers/{district}/{specialization}', [BillController::class, 'getTrainersBySpecialization']);
    Route::post('/trainertravels-store',[BillController::class,'trainerTravelStore'])->name('trainer.travel.store');
    
    Route::get('/trainer-travel-list', [BillController::class, 'trainerTravelList'])->name('trainer.travel.list');
    Route::put('/trainer-travel/{id}', [BillController::class, 'trainerTravelUpdate'])->name('trainer.travel.update');
    Route::post('/trainer-travel/{id}/update-training-date', [BillController::class, 'updateTrainingDate'])->name('trainerTravel.updateTrainingDate');

    Route::get('/trainer-bill-preview', [BillController::class, 'previewFile'])->name('trainer.travel.preview');

    Route::post('/trainer-travel/{id}/approve', [BillController::class, 'approve'])->name('trainerTravel.approve');
    Route::post('/trainer-travel/{id}/reject', [BillController::class, 'reject'])->name('trainerTravel.reject');
    Route::post('/trainer-travel/{id}/revert', [BillController::class, 'revert'])->name('trainerTravel.revert');


    
    Route::get('/uploadcampexpense',[BillController::class,'uploadcampexpense'])->name('uploadexpensebills');
    
    Route::get('/schools', [SchoolController::class, 'index'])->name('student.school');
    Route::get('/district-{id}-schools', [SchoolController::class, 'getSchools']);
    Route::get('/school-{id}-students', [SchoolController::class, 'getStudentsPage'])->name('school.students');
    Route::get('/district-{id}-school-list', [SchoolController::class, 'districtSchoolList'])->name('district.school.list');
    
    Route::get('/district-list', [SchoolController::class, 'index'])->name('select.district');
    Route::get('/district-{id}-list', [SchoolController::class, 'selectdistrictList'])->name('select.school');
    Route::post('/school/{id}/save-training-date', [SchoolController::class, 'saveTrainingDate']);
    Route::get('/district-{id}-schools', [SchoolController::class, 'getSchools']);
    Route::get('/school/{id}/details-json', [SchoolController::class, 'schoolDetailsJson']);
    Route::get('/school/{id}/coordinators-json', [SchoolController::class, 'schoolCoordinatorsJson']);
    Route::get('/school/{id}/trainers-json', [SchoolController::class, 'schoolTrainersJson']);
    Route::get('/school/{id}/staffs-json', [SchoolController::class, 'schoolStaffsJson']);

    // Show list of schools for dlc
    Route::get('/dist-my-schools', [SchoolController::class, 'mySchools'])->name('my.schools');
    // School details
    Route::get('/dist-school-{id}', [SchoolController::class, 'schoolDetails'])->name('dlc.school.details');
    Route::post('/dlc-school-update-{id}', [SchoolController::class, 'updateSchool'])->name('dlc.school.update');

});
Route::middleware([RoleMiddleware::class . ':OKCL,DLC'])->group(function () {
});


Route::middleware([RoleMiddleware::class . ':OCAC,OKCL,DLC,Coordinator'])->group(function () {
    Route::get('/coordinatorlist', [CoordinatorController::class, 'index'])->name('coordinators.index');
    Route::post('/coordinators/store', [CoordinatorController::class, 'store'])->name('coordinators.store');
    Route::get('/coordinators/{coordinator}/edit', [CoordinatorController::class, 'edit'])->name('coordinators.edit');   
    Route::put('/coordinators/{coordinator}', [CoordinatorController::class, 'update'])->name('coordinators.update'); 
    Route::delete('/coordinators/{coordinator}', [CoordinatorController::class, 'destroy'])->name('coordinators.destroy');  
    
    Route::get('/trainerlist', [TrainerController::class, 'index'])->name('trainers.index');
    Route::post('/trainers/store', [TrainerController::class, 'store'])->name('trainers.store');
    Route::get('/trainers/{trainer}/edit', [TrainerController::class, 'edit'])->name('trainers.edit');   
    Route::put('/trainers/{trainer}', [TrainerController::class, 'update'])->name('trainers.update'); 
    Route::delete('/trainers/{trainer}', [TrainerController::class, 'destroy'])->name('trainers.destroy');
    
    Route::get('/supp-staff', [StaffController::class, 'index'])->name('supstaff.index');
    Route::post('/supp-staff/store', [StaffController::class, 'store'])->name('supstaff.store');
    Route::get('/supp-staff/{supstaff}/edit', [StaffController::class, 'edit'])->name('supstaff.edit');   
    Route::put('/supp-staff/{supstaff}', [StaffController::class, 'update'])->name('supstaff.update'); 
    Route::delete('/supp-staff/{supstaff}', [StaffController::class, 'destroy'])->name('supstaff.destroy');  
});

Route::get('/onedrive/login', [OneDriveController::class, 'redirectToProvider'])->name('onedrive.login');
Route::get('/onedrive/callback', [OneDriveController::class, 'handleCallback'])->name('onedrive.callback');


require __DIR__.'/auth.php';
