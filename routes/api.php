<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StudentLoginController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/login',function(){
    return response([
        'status' => false,
        'message' => 'Invalid token'
    ]);
})->name('login');

Route::post('/login',[StudentLoginController::class, 'login']);
Route::post('/createUser', [StudentLoginController::class,'createUser']);


// Route::group(['middleware'=>['auth:sanctum']], function(){
// Route::group(['middleware'=>['auth:api']], function(){
    Route::middleware(['auth:api'])->group(function(){
        Route::get('/getuserDetails',[StudentLoginController::class,'getUserDetails']);
        Route::put('/updateUser/{id}',[StudentLoginController::class,'updateUser']);
        Route::delete('/deleteUser/{id}',[StudentLoginController::class,'deleteUser']);
        Route::get('/logout',[StudentLoginController::class,'logout']);

    });


// });
