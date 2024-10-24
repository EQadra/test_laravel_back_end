<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    // Método para registrar un nuevo usuario
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return response()->json([
                'user' => $this->userResponse($user), // Envía solo los datos necesarios
                'message' => 'Usuario registrado con éxito.',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al registrar el usuario.'], 500);
        }
    }

    // Método para iniciar sesión
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:8',
        ]);

        try {
            if (!$token = JWTAuth::attempt($request->only('email', 'password'))) {
                return response()->json(['error' => 'Credenciales inválidas'], 401);
            }

            $user = JWTAuth::user();
            return response()->json([
                'token' => $token,
                'user' => $this->userResponse($user), // Envía solo los datos necesarios
            ]);
        } catch (JWTException $e) {
            return response()->json(['error' => 'No se pudo crear el token.'], 500);
        }
    }

    // Método para cerrar sesión
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'Sesión cerrada con éxito.']);
    }

    // Método para estructurar la respuesta del usuario
    protected function userResponse(User $user)
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            // Puedes agregar más campos aquí si es necesario
        ];
    }
}
