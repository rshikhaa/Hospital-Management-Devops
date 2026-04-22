<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\PrescriptionController;
use App\Http\Controllers\Api\LabReportController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\MedicalRecordController;
use App\Http\Controllers\Api\InvoiceController;

// CORS headers for API
Route::middleware(['api'])->group(function () {
    Route::options('{any}', function () {
        return response()->json([]);
    })->where('any', '.*');

    Route::get('test', function () {
        return response()->json(['message' => 'API is working']);
    });

    Route::prefix('v1')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('logout', [AuthController::class, 'logout']);
            Route::get('profile', [AuthController::class, 'profile']);
                Route::put('profile', [AuthController::class, 'updateProfile']);
            Route::post('profile/avatar', [AuthController::class, 'uploadAvatar']);
            // serve protected storage files through controller with auth
            Route::get('storage/{path}', [\App\Http\Controllers\Api\DownloadController::class, 'show'])
                ->where('path', '.*');

            Route::get('dashboard', [DashboardController::class, 'summary']);

            Route::get('doctors', [DoctorController::class, 'index']);
            Route::get('doctors/{doctor}', [DoctorController::class, 'show']);

            Route::apiResource('appointments', AppointmentController::class);

            Route::get('prescriptions', [PrescriptionController::class, 'index']);
            Route::get('prescriptions/{prescription}', [PrescriptionController::class, 'show']);

            Route::get('lab-reports', [LabReportController::class, 'index']);
            Route::get('lab-reports/{report}', [LabReportController::class, 'show']);

            Route::apiResource('messages', MessageController::class, ['only' => ['index', 'store', 'show']]);
            // fetch full conversation with a specific patient
            Route::get('messages/thread/{patient}', [MessageController::class, 'thread']);
            
            Route::apiResource('medical-records', MedicalRecordController::class, ['only' => ['index', 'show']]);
            
            Route::apiResource('invoices', InvoiceController::class, ['only' => ['index', 'show']]);

            Route::get('notifications', [NotificationController::class, 'index']);
            Route::post('notifications/{notification}/read', [NotificationController::class, 'markRead']);
        });
    });
});