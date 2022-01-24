<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::apiResource('devices', \App\Http\Controllers\API\DeviceController::class)->only([
    'index', 'store', 'update', 'destroy'
]);
Route::apiResource('storages', \App\Http\Controllers\API\StorageController::class)->only([
    'index', 'store', 'show', 'destroy'
]);
