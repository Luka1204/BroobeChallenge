<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MetricHistoryRunController;
use App\Http\Controllers\StrategyController;
use App\Http\Controllers\CategoryController;



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

Route::get('/',[MetricHistoryRunController::class, 'index'])->name('index');
Route::post('/runMetrics',[MetricHistoryRunController::class, 'runMetrics'])->name('runMetrics');
Route::post('/saveMetrics',[MetricHistoryRunController::class, 'saveMetrics'])->name('saveMetrics');
Route::put('/updateMetrics',[MetricHistoryRunController::class, 'updateMetrics'])->name('updateMetrics');




Route::get('/getStrategies',[StrategyController::class, 'getStrategies'])->name('getStrategies');
Route::get('/getCategories',[CategoryController::class, 'getCategories'])->name('getCategories');
Route::get('/getMetricsHistory',[MetricHistoryRunController::class, 'getMetricsHistory'])->name('getMetricsHistory');



