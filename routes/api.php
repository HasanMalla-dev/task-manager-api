<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('register',[AuthController::class,'register']);
Route::post('login',[AuthController::class,'login']);
Route::middleware('auth:sanctum')->group(function(){
    Route::get('tasks',[TaskController::class,'index']);
    Route::post('task_store',[TaskController::class,'store']);
    Route::get('tasks/{task}',[TaskController::class,'show']);
    Route::patch('tasks/{task}',[TaskController::class,'update']);
    Route::delete('tasks/{task}',[TaskController::class,'destroy']);
    Route::post('logout',[AuthController::class,'logout']);

});
