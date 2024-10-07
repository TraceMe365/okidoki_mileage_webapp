<?php

use App\Http\Controllers\DistanceController;
use App\Http\Controllers\ExcelImportController;
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

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/', [ExcelImportController::class, 'showUploadForm'])->name('excel.uploadform');
Route::post('/import', [ExcelImportController::class, 'import'])->name('excel.import');
Route::post('/distance', [DistanceController::class, 'import'])->name('distance.import');
Route::post('/distanceVia', [DistanceController::class, 'importVia'])->name('distance.import-multiple');
Route::post('/clearmileage', [DistanceController::class, 'clearMileageTable'])->name('clear.mileage');
Route::post('/cleardistancemul', [DistanceController::class, 'clearMultipleDistanceTable'])->name('clear.distancem');
Route::post('/cleardistance', [DistanceController::class, 'clearDistanceTable'])->name('clear.distance');

Route::get('/mileage', function () {
    return view('mileage');
})->name('mileage');

Route::get('/distancepage', function () {
    return view('distancepage');
})->name('distancepage');

Route::get('/multipledistancepage', function () {
    return view('multipledistancepage');
})->name('multipledistancepage');