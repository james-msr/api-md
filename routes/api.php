<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::apiResource('devices', \App\Http\Controllers\API\DeviceController::class)->only([
    'index', 'store', 'update'
]);
Route::apiResource('storages', \App\Http\Controllers\API\StorageController::class)->only([
    'index', 'store', 'show', 'destroy'
]);

Route::get('storages/{storage}/{year}', [\App\Http\Controllers\API\StorageController::class, 'yearReport']);

Route::get('/storages/delete', function () {
    \App\Models\Storage::query()->delete();
    return 201;
});

Route::get('devices/delete', function () {
    \App\Models\Device::query()->delete();
    return 201;
});
