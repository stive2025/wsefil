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
Route::middleware('auth:sanctum')->get('/users',[UserController::class,'index']);
Route::middleware('auth:sanctum')->get('/users/chats',[UserController::class,'indexChats']);
Route::middleware('auth:sanctum')->get('/users/{id}',[UserController::class,'show']);
Route::middleware('auth:sanctum')->post('/users',[UserController::class,'store']);
Route::patch('/users/{id}',[UserController::class,'update']);
Route::middleware('auth:sanctum')->delete('/users/{id}',[UserController::class,'destroy']);

/**
 * ================================> EndPoints para Mensajes
*/

Route::middleware('auth:sanctum')->get('/messages',[MessageController::class,'index']);
Route::middleware('auth:sanctum')->get('/messages/{id}',[MessageController::class,'show']);
Route::middleware('auth:sanctum')->post('/messages/connect',[MessageController::class,'connectmessage']);
Route::middleware('auth:sanctum')->post('/messages/updateACK',[MessageController::class,'updateACK']);
Route::post('/messages',[MessageController::class,'store']);
Route::middleware('auth:sanctum')->patch('/messages/{id}',[MessageController::class,'update']);
Route::middleware('auth:sanctum')->delete('/messages/{id}',[MessageController::class,'destroy']);

 /**
 * ================================> EndPoints para Chats
 */

Route::middleware('auth:sanctum')->get('/chats',[ChatController::class,'index']);
Route::middleware('auth:sanctum')->post('/chats/search/{id}',[ChatController::class,'search']);
Route::get('chats/download',[ChatController::class,'download']);
Route::middleware('auth:sanctum')->get('/chats/{id}',[ChatController::class,'show']);
Route::middleware('auth:sanctum')->post('/chats',[ChatController::class,'store']);
Route::middleware('auth:sanctum')->patch('/chats/{id}',[ChatController::class,'update']);
Route::middleware('auth:sanctum')->put('/chats/transfer/{id}',[ChatController::class,'transfer']);
Route::middleware('auth:sanctum')->delete('/chats/{id}',[ChatController::class,'destroy']);

 /**
 * ================================> EndPoints para Contactos
 */

Route::middleware('auth:sanctum')->get('/contacts',[ContactController::class,'index']);
Route::middleware('auth:sanctum')->get('/contacts/chats',[ContactController::class,'indexChats']);
Route::middleware('auth:sanctum')->get('/contacts/{id}',[ContactController::class,'show']);
Route::middleware('auth:sanctum')->post('/contacts',[ContactController::class,'store']);
Route::middleware('auth:sanctum')->patch('/contacts/{id}',[ContactController::class,'update']);
Route::middleware('auth:sanctum')->delete('/contacts/{id}',[ContactController::class,'destroy']);

 /**
 * ================================> EndPoints para Conexiones de WhatsApp
 */

Route::middleware('auth:sanctum')->get('/connections',[ConnectionController::class,'index']);
Route::get('/connections/{id}',[ConnectionController::class,'show']);
Route::post('/connections',[ConnectionController::class,'store']);
Route::put('/connections/{id}',[ConnectionController::class,'update']);
Route::middleware('auth:sanctum')->delete('/connections/{id}',[ConnectionController::class,'destroy']);
 