<?
// routes/api.php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController; 

// auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->get('/check-login', [AuthController::class, 'checkLogin']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);



//user

Route::get('/get_dashboard', [UserController::class, 'get_dashboard']);
Route::get('/users', [UserController::class, 'getAllUsers']);
Route::put('/users/{id}', [UserController::class, 'update']); // Update user
Route::delete('/users/{id}', [UserController::class, 'destroy']); // Delete user