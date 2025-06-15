<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    // Login: recibe email y password, devuelve token + usuario
    public function login(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');

            if (!$token = Auth::guard('api')->attempt($credentials)) {
                return response()->json(['error' => 'Credenciales inválidas'], 401);
            }

            return response()->json([
                'token' => $token,
                'id' => Auth::guard('api')->user()->id,
                'name' => Auth::guard('api')->user()->name,
                'role' => Auth::guard('api')->user()->role,
            ]);
        } catch (\Throwable $e) {

            return response()->json([
                'error' => 'Error interno del servidor',
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    // Ruta protegida para devolver info del usuario autenticado
    public function me()
    {
        return response()->json(Auth::guard('api')->user());
    }

    // Logout (opcional)
    public function logout()
    {
        Auth::guard('api')->logout();
        return response()->json(['message' => 'Sesión cerrada']);
    }
}
