<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Maneja el proceso de inicio de sesión de un usuario.
     *
     * @param Request $request La solicitud HTTP que contiene las credenciales del usuario (email y password).
     * @return \Illuminate\Http\JsonResponse Una respuesta JSON indicando el éxito o fracaso del inicio de sesión,
     *                                        junto con un token de autenticación si es exitoso.
     */
    function login(Request $request): \Illuminate\Http\JsonResponse
    {
        // Valida los datos de entrada para el inicio de sesión.
        // Se requiere un email válido y una contraseña.
        // Se proporcionan mensajes de error personalizados para cada regla de validación.
        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ], [
            'email.required' => 'El correo es obligatorio.',
            'email.email' => 'El correo debe ser una dirección de correo válida.',
            'password.required' => 'La contraseña es obligatorio.'
        ]);

        // Si la validación falla, devuelve una respuesta JSON con los errores.
        if ($validate->fails()) {
            return response()->json([
                'success' => 0,
                'message' => 'Error de validación',
                'errors' => $validate->errors(),
            ]);
        }

        // Busca al usuario por su dirección de correo electrónico.
        $user = User::where('email', $request->email)->first();

        // Verifica si el usuario existe y si la contraseña proporcionada coincide con la almacenada.
        if (! $user || ! Hash::check($request->password, $user->password)) {
            // Si el usuario no existe o la contraseña es incorrecta, devuelve un error.
            return response()->json([
                'success' => 0,
                'message' => 'Correo y/o contraseña incorrectos.',
            ]);
        }

        $roleName = $user->role->name;

        // Elimina todos los tokens existentes del usuario para asegurar que solo haya un token activo por sesión.
        $user->tokens()->delete();
        // Crea un nuevo token de autenticación para el usuario con el nombre de su rol como capacidad.
        $token = $user->createToken($request->email, [$roleName])->plainTextToken;

        // Devuelve una respuesta exitosa con el rol del usuario y el token de autenticación.
        return response()->json([
            'success' => 1,
            'name'=> $user->name,
            'role' => $roleName,
            'data' => $token,
        ]);
    }

    /**
     * Cierra la sesión del usuario actual eliminando su token de acceso.
     *
     * @param Request $request La solicitud HTTP que contiene el usuario autenticado.
     * @return \Illuminate\Http\JsonResponse Una respuesta JSON indicando el éxito del cierre de sesión.
     */
    function logout(Request $request): \Illuminate\Http\JsonResponse
    {
        // Elimina el token de acceso actual del usuario autenticado.
        $request->user()->currentAccessToken()->delete();

        // Devuelve una respuesta exitosa indicando que la sesión ha sido cerrada.
        return response()->json([
            'success' => 1,
            'message' => 'Sesión cerrada exitosamente.',
        ]);
    }
}
