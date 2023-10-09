<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        
        if (Auth::attempt($credentials)) {
            // Inicio de sesión exitoso
            $user = Auth::user();
            $token = $user->createToken('API Token')->accessToken;
        
            return response()->json([
                'token' => $token, // Cambia 'token' a 'api_token'
                'name' => $user->name,
                'email' => $user->email,
                'user_id' => $user->id,
                'api_token'=>$user->api_token
            ], 200);
        } else {
            // Inicio de sesión fallido
            return response()->json(['error' => 'Credenciales inválidas'], 401);
        }
    }    
}
