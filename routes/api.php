<?php

use App\Http\Controllers\ExcelUploadController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


/**
 * TASK API
 */

Route::apiResource('tasks', TaskController::class);
Route::post('tasks/upload-excel', [ExcelUploadController::class, 'uploadExcel']);
