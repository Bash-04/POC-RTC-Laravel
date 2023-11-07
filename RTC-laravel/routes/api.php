<?php

use App\Http\Controllers\ItemController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/test', function () {
    return "test";
});
Route::post('/user', [UserController::class, 'CreateUser']);
Route::get('/user', [UserController::class, 'GetUser']);
Route::get('/users', [UserController::class, 'GetUsers']);

Route::post('/item', [ItemController::class, 'CreateItem']);
Route::get('/items', [ItemController::class, 'GetItems']);
Route::get('/items/available', [ItemController::class, 'GetAvailableItems']);
Route::post('/item/reserve', [ItemController::class, 'ReserveItem']);
Route::delete('/item/reserve', [ItemController::class, 'DeleteReservation']);
