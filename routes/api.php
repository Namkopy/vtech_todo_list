<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\todoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/todo', [todoController::class, 'show']);
Route::get('/todo/{id}', [todoController::class, 'detail']);
Route::post('/todo', [todoController::class, 'add']);
Route::put('/todo/{id}', [todoController::class, 'update']);
Route::delete('/todo/{id}', [todoController::class, 'delete']);
Route::put('/todoComplete/{id}', [todoController::class, 'todoComplete']);
