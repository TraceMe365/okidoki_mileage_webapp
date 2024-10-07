<?php

use App\Http\Controllers\DistanceController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\ExcelImportController;
use Illuminate\Support\Facades\Route;

// Import
Route::get('/', [ExcelImportController::class, 'showUploadForm'])->name('excel.uploadform');
Route::post('/import', [ExcelImportController::class, 'import'])->name('excel.import');
Route::post('/distance', [DistanceController::class, 'import'])->name('distance.import');
Route::post('/distanceVia', [DistanceController::class, 'importVia'])->name('distance.import-multiple');

// Clear
Route::post('/clearmileage', [DistanceController::class, 'clearMileageTable'])->name('clear.mileage');
Route::post('/cleardistancemul', [DistanceController::class, 'clearMultipleDistanceTable'])->name('clear.distancem');
Route::post('/cleardistance', [DistanceController::class, 'clearDistanceTable'])->name('clear.distance');


// Downloads
Route::get('/downloadMileage', [DownloadController::class, 'downloadMileage'])->name('download.mileage');
Route::get('/downloadDistance', [DownloadController::class, 'downloadDistance'])->name('download.distance');
Route::get('/downloadDistanceM', [DownloadController::class, 'downloadDistanceMultiple'])->name('download.distancem');

// Views
Route::get('/mileage', function () {
    return view('mileage');
})->name('mileage');

Route::get('/distancepage', function () {
    return view('distancepage');
})->name('distancepage');

Route::get('/multipledistancepage', function () {
    return view('multipledistancepage');
})->name('multipledistancepage');