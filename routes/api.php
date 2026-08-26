<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AuthController,PersonalInfoController};

Route::post('login',[AuthController::class,'login']);

Route::middleware(['auth:sanctum'])->group(function(){
    Route::post('personal-info',[PersonalInfoController::class,'store']);
});

