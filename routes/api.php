<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IbgesController;
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

Route::middleware('auth:sanctum')->group(function(){
    Route::apiResource('/alimentos/ibge',IbgesController::class);
    
    Route::patch('/alimentos/ibge/{$id}',[IbgesController::class,'status']);
});


Route::post('/login',function(Request $request){
    $credentials = $request->only(['email','password']);
    $user = User::whereEmail($credentials['email'])->first();


    if(Auth::attempt($credentials) === false) return response()->json('Unauthorized',401);
    
    $user = Auth::user();
    $user->tokens()->delete();
    $token = $user->createToken('token');
    return response()->json($token->plainTextToken);
});