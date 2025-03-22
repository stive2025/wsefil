<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\ConnectionController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/**
 * ================================> EndPoints para Usuarios
 */

Route::post('/login',[LoginController::class,'login']);
Route::get('/users',[UserController::class,'index']);
Route::get('/users/{id}',[UserController::class,'show']);
Route::post('/users',[UserController::class,'store']);
Route::patch('/users/{id}',[UserController::class,'update']);
Route::delete('/users/{id}',[UserController::class,'destroy']);

 /**
 * ================================> EndPoints para Mensajes
 */

Route::get('/messages',[MessageController::class,'index']);
Route::get('/messages/{id}',[MessageController::class,'show']);
Route::post('/messages/updateACK',[MessageController::class,'updateACK']);
Route::post('/messages',[MessageController::class,'store']);
Route::patch('/messages/{id}',[MessageController::class,'update']);
Route::delete('/messages/{id}',[MessageController::class,'destroy']);

 /**
 * ================================> EndPoints para Chats
 */

Route::get('/chats',[ChatController::class,'index']);
Route::get('/chats/{id}',[ChatController::class,'show']);
Route::post('/chats',[ChatController::class,'store']);
Route::patch('/chats/{id}',[ChatController::class,'update']);
Route::delete('/chats/{id}',[ChatController::class,'destroy']);

 /**
 * ================================> EndPoints para Contactos
 */

Route::get('/contacts',[ContactController::class,'index']);
Route::get('/contacts/{id}',[ContactController::class,'show']);
Route::post('/contacts',[ContactController::class,'store']);
Route::patch('/contacts/{id}',[ContactController::class,'update']);
Route::delete('/contacts/{id}',[ContactController::class,'destroy']);

 /**
 * ================================> EndPoints para Conexiones de WhatsApp
 */

Route::get('/connections',[ConnectionController::class,'index']);
Route::get('/connections/{id}',[ConnectionController::class,'show']);
Route::post('/connections',[ConnectionController::class,'store']);
Route::put('/connections/{id}',[ConnectionController::class,'update']);
Route::delete('/connections/{id}',[ConnectionController::class,'destroy']);
 