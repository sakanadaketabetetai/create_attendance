<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\RestController;
use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\ApprovalController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request; 

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

Route::middleware(['auth:web','verified'])->group(function(){
    Route::get('/attendance', [AttendanceController::class,'attendance']);
    Route::get('/attendance/clock_in', [AttendanceController::class,'attendance_clock_in']);
    Route::get('/attendance/clock_out', [AttendanceController::class,'attendance_clock_out']);
    Route::get('/attendance/rest_start', [RestController::class,'rest_start']);
    Route::get('/attendance/rest_end', [RestController::class,'rest_end']);
    Route::get('/attendance/list/{num}', [AttendanceController::class,'attendance_index']);
    Route::get('/attendance/{id}', [AttendanceController::class,'attendance_detail'])->name('attendance_detail');
    Route::post('/attendance/stamp_correction_request', [ApprovalController::class,'application']);
    Route::post('/stamp_correction_request/list', [ApprovalController::class,'application_list']);
});


//会員登録後のメール認証処理
Route::get('/email/verify', function(){
    return view('auth.verify-email');
})->middleware(['auth'])->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/attendance'); // 認証後のリダイレクト先を指定
})->middleware(['auth', 'signed'])->name('verification.verify');

// メール認証通知の再送信
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');


//管理者用ルーティング

Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'showLoginPage'])->name('admin.login');
    Route::post('/login', [AdminLoginController::class, 'login']);
    Route::middleware(['auth:admin'])->group(function(){
        Route::get('/attendance/list/{num}', [AdminController::class, 'admin_attendance_list']);
        Route::get('/attendance/{id}', [AdminController::class, 'admin_attendance_detail']);
        Route::post('/attendance/update', [AdminController::class, 'admin_attendance_update']);
        Route::get('/attendance/staff/list', [AdminController::class, 'admin_staff_list']);
        Route::post('/attendance/staff/export', [AdminController::class, 'attendance_export']);
        Route::get('/attendance/staff/{id}/{num}', [AdminController::class, 'admin_staff_attendance_list']);
        Route::post('/stamp_correction_request/list', [AdminController::class,'admin_application_list']);
        Route::get('/stamp_correction_request/approve/{id}', [AdminController::class,'admin_application_detail']);
        Route::post('/stamp_correction_request/approve/{id}', [AdminController::class,'admin_application_approve']);
    });
});

