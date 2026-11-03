<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RekController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\SppController;
use App\Http\Controllers\BkkController;
use App\Http\Controllers\UnitController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group([
    'middleware' => 'api'
], function ($router) {
    
    Route::get('/', function () {
        return view('welcome');
    });
    

    Route::group(['prefix' => 'coa'], function() {
        Route::get('level1', [RekController::class, 'indexRek1']);
        Route::get('level6', [RekController::class, 'indexRek6'])->middleware('auth.jwt');
    });
    
    Route::group(['prefix' => 'journal'], function() {
        Route::post('spm', [JournalController::class, 'store'])->middleware('auth.jwt');
    });
    
    Route::group(['prefix' => 'kegiatan'], function() {
        Route::get('', [KegiatanController::class, 'index'])->middleware('auth.jwt');
        Route::get('{id}/rinci', [KegiatanController::class, 'showByIdWithRinci'])->middleware('auth.jwt');
        Route::get('index/spp', [KegiatanController::class, 'spp'])->middleware('auth.jwt');
        Route::get('index/spm', [KegiatanController::class, 'spm'])->middleware('auth.jwt');
    });
    
    Route::group(['prefix' => 'spp'], function() {
        Route::get('', [SppController::class, 'index'])->middleware('auth.jwt');
        Route::get('{id}/rinci', [SppController::class, 'showByIdWithRinci'])->middleware('auth.jwt');
    });
    
    Route::group(['prefix' => 'bkk'], function() {
        Route::get('', [BkkController::class, 'index'])->middleware('auth.jwt');
        Route::get('{id}/spm', [BkkController::class, 'showByIdWithSpp'])->middleware('auth.jwt');
    });
    
    Route::group(['prefix' => 'spm'], function() {
        Route::get('', [SppController::class, 'spm'])->middleware('auth.jwt');
        Route::get('show', [SppController::class, 'spmShow'])->middleware('auth.jwt');
    });
 
});


Route::group([
    'middleware' => ['auth.jwt']
], function ($router) {
    Route::group(['prefix' => 'unit'], function() {
        Route::get('', [UnitController::class, 'index']);
    });
});


