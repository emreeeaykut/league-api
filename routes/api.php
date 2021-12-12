<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\MatcheController;
use App\Http\Controllers\API\PointController;
use App\Http\Controllers\API\TeamController;
use Illuminate\Support\Facades\Route;

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

Route::post('/auth/register', [AuthController::class, 'register']);

Route::post('/auth/login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/profile', function() {
        return auth()->user();
    });

    Route::post('/auth/logout', [AuthController::class, 'logout']);
});

Route::resource('teams', TeamController::class);

Route::post('/teams/{id}/points', [TeamController::class, 'pointStore']);

Route::get('/teams/{id}/points/{pointId}', [TeamController::class, 'pointShow']);

Route::put('/teams/{id}/points/{pointId}', [TeamController::class, 'pointUpdate']);

Route::delete('/teams/{id}/points/{pointId}', [TeamController::class, 'pointDestroy']);

Route::get('/teams/{id}/next-matche', [TeamController::class, 'nextMatche']);

Route::resource('matches', MatcheController::class);

Route::get('/matches/{id}/play', [MatcheController::class, 'play']);

Route::middleware('auth:sanctum')->group(function() {
    Route::resource('points', PointController::class);
});

Route::get('/points', [PointController::class, 'index']);
