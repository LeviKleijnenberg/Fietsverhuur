<?php

use Illuminate\Support\Facades\Route;


Route::post('/assistance-request', [App\Http\Controllers\Api\AssistanceRequestController::class, 'store']);

